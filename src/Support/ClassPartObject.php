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
 * @template TAnyClassGroupId of string
 * @template TAnyThemeGroupId of string
 *
 * @phpstan-import-type ThemeObject from ConfigGroupPart
 * @phpstan-import-type ClassGroup from ConfigGroupPart
 *
 * @phpstan-type ClassValidatorObject = array{
 *     classGroupId: TAnyClassGroupId,
 *     validator: ClassValidator
 * }
 */
final class ClassPartObject
{
    private const string CLASS_PART_SEPARATOR = '-';

    private const string ARBITRARY_PROPERTY_PATTERN = '/^\[(.+)]$/';

    public function __construct(
        /**
         * @var array<string, ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>>
         */
        protected(set) array $nextPart = [],

        /**
         * @var array<ClassValidatorObject>
         */
        protected(set) array $validators = [],

        /**
         * @var ?TAnyClassGroupId
         */
        protected(set) ?string $classGroupId = null,
    ) {}

    /**
     * @param  Config<TAnyClassGroupId, TAnyThemeGroupId>  $config
     * @return array{
     *     getClassGroupId: Closure(string): ?TAnyClassGroupId,
     *     getConflictingClassGroupIds: Closure(TAnyClassGroupId, bool): array<TAnyClassGroupId>
     * }
     */
    public static function createClassGroupUtils(Config $config): array
    {
        $classMap = self::createClassMap($config);

        /**
         * @param  string  $className
         * @return ?TAnyClassGroupId
         */
        $getClassGroupId = function (string $className) use ($classMap): ?string {
            $classParts = explode(self::CLASS_PART_SEPARATOR, $className);

            // Classes like `-inset-1` produce an empty string as first classPart. We assume that classes for negative values are used correctly and remove it from classParts.
            if ('' === $classParts[0] || 1 !== count($classParts)) {
                array_shift($classParts);
            }

            return self::getGroupRecursive($classParts, $classMap)
                ?: self::getGroupIdForArbitraryProperty($className);
        };

        /**
         * @param  TAnyClassGroupId  $classGroupId
         * @param  bool  $hasPostPrefixModifier
         * @return array<TAnyClassGroupId>
         */
        $getConflictingClassGroupIds = function (string $classGroupId, bool $hasPostPrefixModifier) use ($config): array {
            /** @var array<TAnyClassGroupId> $conflicts */
            $conflicts = $config->conflictingClassGroups[$classGroupId] ?? [];

            /** @var array<TAnyClassGroupId> $conflictingClassGroupModifiers */
            $conflictingClassGroupModifiers = $config->conflictingClassGroupModifiers[$classGroupId] ?? [];

            if ($hasPostPrefixModifier && $conflictingClassGroupModifiers) {
                $conflicts = [...$conflicts, ...$conflictingClassGroupModifiers];
            }

            return $conflicts;
        };

        /**
         * @var Closure(string): ?TAnyClassGroupId $getClassGroupId
         */
        return compact('getClassGroupId', 'getConflictingClassGroupIds');
    }

    /**
     * @param  Config<TAnyClassGroupId, TAnyThemeGroupId>  $config
     * @return ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>
     */
    public static function createClassMap(Config $config): self
    {
        /** @var self<TAnyClassGroupId, TAnyThemeGroupId> $classMap */
        $classMap = new self();

        foreach ($config->classGroups as $classGroupId => $classGroup) {
            self::processClassesRecursively(
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
     * @param  ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>  $classPartObjects
     * @return ?TAnyClassGroupId
     */
    private static function getGroupRecursive(array $classParts, ClassPartObject $classPartObjects): ?string
    {
        if ( ! $classParts) {
            return $classPartObjects->classGroupId;
        }

        $currentClassPart = $classParts[0];
        $nextClassPartObject = $classPartObjects->nextPart[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject
            ? self::getGroupRecursive(
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

        $classRest = implode(self::CLASS_PART_SEPARATOR, $classParts);

        $validator = Arr::first(
            $classPartObjects->validators,
            static fn (array $validator) => $validator['validator']($classRest)
        );

        return $validator ? $validator['classGroupId'] : null;
    }

    private static function getGroupIdForArbitraryProperty(string $className): ?string
    {
        $arbitraryPropertyClassName = Str::match(self::ARBITRARY_PROPERTY_PATTERN, $className);

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
     * @param  ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>  $classPartObject
     * @param  TAnyClassGroupId  $classGroupId
     * @param  ThemeObject  $theme
     */
    private static function processClassesRecursively(
        array $classGroup,
        ClassPartObject $classPartObject,
        string $classGroupId,
        array $theme
    ): void {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartObjectToEdit = '' === $classDefinition
                    ? $classPartObject
                    : self::getPart($classPartObject, $classDefinition);
                $classPartObjectToEdit->classGroupId = $classGroupId;
            } elseif ($classDefinition instanceof ThemeGetter) {
                self::processClassesRecursively(
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
                    self::processClassesRecursively(
                        $classGroup,
                        self::getPart($classPartObject, $key),
                        $classGroupId,
                        $theme
                    );
                }
            }
        }
    }

    /**
     * @param  ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>  $classPartObject
     * @return ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId>
     */
    private static function getPart(ClassPartObject $classPartObject, string $path): ClassPartObject
    {
        $currentClassPartObject = $classPartObject;

        foreach (explode(self::CLASS_PART_SEPARATOR, $path) as $pathPart) {
            $currentClassPartObject->nextPart[$pathPart] ??= new ClassPartObject(
                nextPart: [],
                validators: [],
                classGroupId: null,
            );

            $currentClassPartObject = $currentClassPartObject->nextPart[$pathPart];
        }

        /** @var ClassPartObject<TAnyClassGroupId, TAnyThemeGroupId> */
        return $currentClassPartObject;
    }
}
