<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lumen\TwMerge\Support\Config\Utils;
use Lumen\TwMerge\Support\Contracts\Config;

/**
 * @phpstan-import-type AnyClassGroupIds from Config
 */
class ClassListMerger
{
    /**
     * @var non-empty-string
     */
    protected const string SPLIT_CLASSES_PATTERN = '/\s+/';

    public static function merge(string $classList, Utils $configUtils): string
    {
        /**
         * My IDE wasn't able to infer types from PHPStan types, so I had to add them here.
         *
         * @var Closure(string): ?string $getClassGroupId
         * @var Closure(AnyClassGroupIds, bool): array<AnyClassGroupIds> $getConflictingClassGroupIds
         * @var Closure(string): ParsedClassName $parseClassName
         * @var Closure(array<string>): array<string> $sortModifiers
         */
        [
            'getClassGroupId' => $getClassGroupId,
            'getConflictingClassGroupIds' => $getConflictingClassGroupIds,
            'parseClassName' => $parseClassName,
            'sortModifiers' => $sortModifiers
        ] = $configUtils->destructureUtils();

        /**
         * Set of classGroupIds in following format:
         * `{importantModifier}{variantModifiers}{classGroupId}`
         *
         * @example 'float'
         * @example 'hover:focus:bg-color'
         * @example 'md:!pr'
         *
         * @var array<string> $classGroupsInConflict
         */
        $classGroupsInConflict = [];

        /** @var Collection<int, string> $classNames */
        $classNames = Str::of($classList)
            ->trim()
            ->split(static::SPLIT_CLASSES_PATTERN);

        /** @var array<string> $result */
        $result = [];

        for ($index = $classNames->count() - 1; $index >= 0; $index--) {
            /** @var string $originalClassName */
            $originalClassName = $classNames->get($index);

            $parsedClassName = $parseClassName($originalClassName);

            $isExternal = $parsedClassName->isExternal;
            $modifiers = $parsedClassName->modifiers;
            $hasImportantModifier = $parsedClassName->hasImportantModifier;
            $baseClassName = $parsedClassName->baseClassName;
            $maybePostfixModifierPosition = $parsedClassName->maybePostfixModifierPosition;

            if ($isExternal) {
                $result = Arr::prepend($result, $originalClassName);

                continue;
            }

            $hasPostfixModifier = (bool) $maybePostfixModifierPosition;
            $classGroupId = $getClassGroupId(
                $hasPostfixModifier
                    ? Str::substr($baseClassName, 0, $maybePostfixModifierPosition)
                    : $baseClassName
            );

            if ( ! $classGroupId) {
                if ( ! $hasPostfixModifier) {
                    // Not a tailwind class
                    $result = Arr::prepend($result, $originalClassName);

                    continue;
                }

                $classGroupId = $getClassGroupId($baseClassName);

                if ( ! $classGroupId) {
                    // Not a Tailwind class
                    $result = Arr::prepend($result, $originalClassName);

                    continue;
                }

                $hasPostfixModifier = false;
            }

            $variantModifier = Arr::join($sortModifiers($modifiers), ':');

            $modifierId = $hasImportantModifier
                ? $variantModifier . ParsedClassName::IMPORTANT_MODIFIER
                : $variantModifier;

            $classId = $modifierId . $classGroupId;

            if (in_array($classId, $classGroupsInConflict)) {
                // Tailwind class omitted due to conflict
                continue;
            }

            $classGroupsInConflict[] = $classId;

            $conflictGroups = $getConflictingClassGroupIds($classGroupId, $hasPostfixModifier);
            for ($i = 0; $i < count($conflictGroups); $i++) {
                $group = $conflictGroups[$i];
                $classGroupsInConflict[] = $modifierId . $group;
            }

            // Tailwind class not in conflict
            $result = Arr::prepend($result, $originalClassName);
        }

        return Arr::toCssClasses($result);
    }
}
