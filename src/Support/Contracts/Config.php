<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * @template TClassGroupIds of string
 * @template TThemeGroupIds of string
 *
 * @template-covariant TClassGroupIds
 * @template-covariant TThemeGroupIds
 *
 * @phpstan-import-type ThemeObject from ConfigGroupPart
 * @phpstan-import-type ClassGroup from ConfigGroupPart
 *
 * @phpstan-type AnyClassGroupIds = string
 * @phpstan-type AnyThemeGroupIds = string
 * @phpstan-type AnyConfig = Config<AnyClassGroupIds, AnyThemeGroupIds>
 *     Type of the tailwind-merge configuration that allows for any possible configuration.
 *
 * @extends ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
 */
interface Config extends ConfigGroupPart, ConfigStaticPart
{
    /**
     * @param  ThemeObject  $theme
     * @param  array<TClassGroupIds, ClassGroup>  $classGroups
     * @param  array<TClassGroupIds, TClassGroupIds>  $conflictingClassGroups
     * @param  array<TClassGroupIds, TClassGroupIds>  $conflictingGroupModifiers
     * @param  array<string>  $orderSensitiveModifiers
     */
    public function __construct(
        ?int $cacheSize = null,
        ?string $prefix = null,
        array $theme = [],
        array $classGroups = [],
        array $conflictingClassGroups = [],
        array $conflictingGroupModifiers = [],
        array $orderSensitiveModifiers = [],
    );
}
