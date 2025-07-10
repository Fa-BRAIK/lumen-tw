<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Lumen\TwMerge\Support\Contracts\ClassValidator;
use Lumen\TwMerge\Support\ValueObjects\ClassPartObject;
use Lumen\TwMerge\Support\ValueObjects\ThemeGetter;

class ClassMap
{
    public const string CLASS_PART_SEPARATOR = ClassParser::CLASS_PART_SEPARATOR;

    protected function __construct() {}

    /**
     * @param array{
     *     cacheSize: int|null,
     *     prefix: string|null,
     *     theme: array<string, array<array-key, mixed>>,
     *     classGroups: array<string, array<array-key, mixed>>,
     *     conflictingClassGroups: array<string, array<array-key, mixed>>,
     *     conflictingClassGroupModifiers: array<string, array<array-key, mixed>>,
     *     orderSensitiveModifiers: array<string>
     * } $config
     */
    public static function fromConfig(array $config): ClassPartObject
    {
        $classMap = new ClassPartObject();

        foreach ($config['classGroups'] as $classGroupId => $classGroup) {
            self::processClassesRecursively(
                classPartObject: $classMap,
                classGroupId: $classGroupId,
                classGroup: $classGroup,
                theme: $config['theme']
            );
        }

        return $classMap;
    }

    /**
     * @param  array<array-key, mixed>  $classGroup
     * @param  array<string, array<array-key, mixed>>  $theme
     */
    public static function processClassesRecursively(
        ClassPartObject $classPartObject,
        string $classGroupId,
        array $classGroup,
        array $theme
    ): void {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartObjectToEdit = '' === $classDefinition
                    ? $classPartObject
                    : self::getPart($classPartObject, $classDefinition);

                $classPartObjectToEdit->withClassGroupId($classGroupId);
            } elseif ($classDefinition instanceof ThemeGetter) {
                self::processClassesRecursively(
                    classPartObject: $classPartObject,
                    classGroupId: $classGroupId,
                    classGroup: $classDefinition->get($theme),
                    theme: $theme,
                );
            } elseif ($classDefinition instanceof ClassValidator) {
                $classPartObject->pushClassValidator(
                    classGroupId: $classGroupId,
                    validator: $classDefinition
                );
            } elseif (is_array($classDefinition)) {
                foreach ($classDefinition as $key => $classGroup) {
                    if ( ! is_array($classGroup)) {
                        continue;
                    }

                    /** @var array<string, mixed> $classGroup */
                    self::processClassesRecursively(
                        classPartObject: self::getPart($classPartObject, $key),
                        classGroupId: $classGroupId,
                        classGroup: $classGroup,
                        theme: $theme,
                    );
                }
            }
        }
    }

    protected static function getPart(ClassPartObject $classPartObject, string $path): ClassPartObject
    {
        $currentClassPartObject = $classPartObject;

        foreach (explode(self::CLASS_PART_SEPARATOR, $path) as $pathPart) {
            $currentClassPartObject = $currentClassPartObject->putNonExistingPart(
                pathPart: $pathPart,
                nextPart: $classPartObject
            );
        }

        return $currentClassPartObject;
    }
}
