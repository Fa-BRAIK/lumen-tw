<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lumen\TwMerge\Support\Contracts\ClassValidator;
use Lumen\TwMerge\Support\Contracts\Config;
use Lumen\TwMerge\Support\Contracts\ConfigGroupPart;

/**
 * @phpstan-import-type AnyClassGroupIds from Config
 * @phpstan-import-type AnyThemeGroupIds from Config
 * @phpstan-import-type AnyConfig from Config
 * @phpstan-import-type ThemeObject from ConfigGroupPart
 * @phpstan-import-type ClassGroup from ConfigGroupPart
 *
 * @phpstan-type ClassValidatorObject = array{
 *     classGroupId: AnyClassGroupIds,
 *     validator: ClassValidator
 * }
 * @phpstan-type GetClassGroupIdClosure = Closure(string): ?AnyClassGroupIds
 * @phpstan-type GetConflictingClassGroupIdsClosure = Closure(AnyClassGroupIds, bool): array<AnyClassGroupIds>
 *
 * @phpstan-consistent-constructor
 */
class ClassPartObject
{
    /**
     * @var non-empty-string
     */
    protected const string CLASS_PART_SEPARATOR = '-';

    /**
     * @var non-empty-string
     */
    protected const string ARBITRARY_PROPERTY_PATTERN = '/^\[(.+)]$/';

    public function __construct(
        /**
         * @var array<string, ClassPartObject>
         */
        protected(set) array $nextPart = [],

        /**
         * @var array<ClassValidatorObject>
         */
        protected(set) array $validators = [],

        /**
         * @var ?AnyClassGroupIds
         */
        protected(set) ?string $classGroupId = null,
    ) {}

    /**
     * @param  AnyConfig  $config
     * @return array{
     *     getClassGroupId: GetClassGroupIdClosure,
     *     getConflictingClassGroupIds: GetConflictingClassGroupIdsClosure
     * }
     */
    public static function createClassGroupUtils(Config $config): array
    {
        $classMap = static::createClassMap($config);

        /**
         * @param  string  $className
         * @return ?AnyClassGroupIds
         */
        $getClassGroupId = function (string $className) use ($classMap): ?string {
            $classParts = explode(static::CLASS_PART_SEPARATOR, $className);

            // Classes like `-inset-1` produce an empty string as first classPart. We assume that classes for negative values are used correctly and remove it from classParts.
            if ('' === $classParts[0] && 1 !== count($classParts)) {
                array_shift($classParts);
            }

            return static::getGroupRecursive($classParts, $classMap)
                ?: static::getGroupIdForArbitraryProperty($className);
        };

        /**
         * @param  AnyClassGroupIds  $classGroupId
         * @param  bool  $hasPostPrefixModifier
         * @return array<AnyClassGroupIds>
         */
        $getConflictingClassGroupIds = function (string $classGroupId, bool $hasPostPrefixModifier) use ($config): array {
            /** @var array<AnyClassGroupIds> $conflicts */
            $conflicts = $config->conflictingClassGroups[$classGroupId] ?? [];

            /** @var array<AnyClassGroupIds> $conflictingClassGroupModifiers */
            $conflictingClassGroupModifiers = $config->conflictingClassGroupModifiers[$classGroupId] ?? [];

            if ($hasPostPrefixModifier && $conflictingClassGroupModifiers) {
                $conflicts = [...$conflicts, ...$conflictingClassGroupModifiers];
            }

            return $conflicts;
        };

        /**
         * @var Closure(string): ?AnyClassGroupIds $getClassGroupId
         */
        return compact('getClassGroupId', 'getConflictingClassGroupIds');
    }

    /**
     * @param  AnyConfig  $config
     */
    public static function createClassMap(Config $config): static
    {
        $classMap = new static();

        foreach ($config->classGroups as $classGroupId => $classGroup) {
            static::processClassesRecursively(
                $classGroup,
                $classMap,
                $classGroupId,
                $config->theme
            );
        }

        return $classMap;
    }

    /**
     * @param  array<string>  $classParts
     * @return ?AnyClassGroupIds
     */
    protected static function getGroupRecursive(array $classParts, ClassPartObject $classPartObjects): ?string
    {
        if ( ! $classParts) {
            return $classPartObjects->classGroupId;
        }

        $currentClassPart = $classParts[0];
        $nextClassPartObject = $classPartObjects->nextPart[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject
            ? static::getGroupRecursive(
                array_slice($classParts, 1),
                $nextClassPartObject
            )
            : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }
        if ( ! $classPartObjects->validators) {
            return null;
        }

        $classRest = implode(static::CLASS_PART_SEPARATOR, $classParts);

        $validator = Arr::first(
            $classPartObjects->validators,
            static fn (array $validator) => $validator['validator']($classRest)
        );

        return $validator ? $validator['classGroupId'] : null;
    }

    protected static function getGroupIdForArbitraryProperty(string $className): ?string
    {
        $arbitraryPropertyClassName = Str::match(static::ARBITRARY_PROPERTY_PATTERN, $className);

        if ($arbitraryPropertyClassName) {
            $property = Str::before($arbitraryPropertyClassName, ':');

            if ($property) {
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..' . $property;
            }
        }

        return null;
    }

    /**
     * @param  ClassGroup  $classGroup
     * @param  AnyClassGroupIds  $classGroupId
     * @param  ThemeObject  $theme
     */
    protected static function processClassesRecursively(
        array $classGroup,
        ClassPartObject $classPartObject,
        string $classGroupId,
        array $theme
    ): void {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartObjectToEdit = '' === $classDefinition
                    ? $classPartObject
                    : static::getPart($classPartObject, $classDefinition);
                $classPartObjectToEdit->classGroupId = $classGroupId;
            } elseif ($classDefinition instanceof ThemeGetter) {
                static::processClassesRecursively(
                    $classDefinition->get($theme),
                    $classPartObject,
                    $classGroupId,
                    $theme
                );
            } elseif ($classDefinition instanceof ClassValidator) {
                $classPartObject->validators[] = [
                    'classGroupId' => $classGroupId,
                    'validator' => $classDefinition,
                ];
            } else {
                foreach ($classDefinition as $key => $classGroup) {
                    static::processClassesRecursively(
                        $classGroup,
                        static::getPart($classPartObject, $key),
                        $classGroupId,
                        $theme
                    );
                }
            }
        }
    }

    protected static function getPart(ClassPartObject $classPartObject, string $path): ClassPartObject
    {
        $currentClassPartObject = $classPartObject;

        foreach (explode(static::CLASS_PART_SEPARATOR, $path) as $pathPart) {
            $currentClassPartObject->nextPart[$pathPart] ??= new ClassPartObject(
                nextPart: [],
                validators: [],
                classGroupId: null,
            );

            $currentClassPartObject = $currentClassPartObject->nextPart[$pathPart];
        }

        /** @var ClassPartObject */
        return $currentClassPartObject;
    }
}
