<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

use Lumen\TwMerge\Support\ValueObjects\ThemeGetter;

/**
 * The dynamic part of the tailwind-merge configuration.
 * When merging multiple configurations, the user can choose to either override or extend the properties of this interface.
 *
 * @template TClassGroupIds of string
 * @template TThemeGroupIds of string
 *
 * @note On PHPStan: I cannot use `recursive-typing` here because it is not supported in PHPStan,
 *              I cannot use Generics either here. https://github.com/phpstan/phpstan/issues/3006
 *
 * @phpstan-type ThemeObject = array<string, ClassGroup>
 * @phpstan-type ClassGroup = array<ClassDefinition>
 * @phpstan-type ClassDefinition = string|ClassValidator|ThemeGetter|ClassObject
 * @phpstan-type ClassObject = array<string, array<string|ClassValidator|ThemeGetter>>
 */
interface ConfigGroupPart
{
    /**
     * Theme scales used in classGroups.
     *
     * The keys are the same as in the Tailwind config but the values are sometimes defined more broadly.
     *
     * @var ThemeObject
     */
    public array $theme { get; }

    /**
     * Object with groups of classes.
     *
     * @example
     * {
     *      // Creates group of classes `group`, `of` and `classes`
     *      'group-id': ['group', 'of', 'classes'],
     *      // Creates group of classes `look-at-me-other` and `look-at-me-group`.
     *      'other-group': [{ 'look-at-me': ['other', 'group']}]
     *  }
     *
     * @var array<TClassGroupIds, ClassGroup>
     */
    public array $classGroups { get; }

    /**
     * Conflicting classes across groups.
     *
     * The key is the ID of a class group which creates a conflict, values are IDs of class groups which receive a conflict. That means if a class from from the key ID is present, all preceding classes from the values are removed.
     *
     * A class group ID is the key of a class group in the classGroups object.
     *
     * @example { gap: ['gap-x', 'gap-y'] }
     *
     * @var array<TClassGroupIds, array<TClassGroupIds>>
     */
    public array $conflictingClassGroups { get; }

    /**
     * Postfix modifiers conflicting with other class groups.
     *
     * A class group ID is the key of a class group in classGroups object.
     *
     * @example { 'font-size': ['leading'] }
     *
     * @var array<TClassGroupIds, array<TClassGroupIds>>
     */
    public array $conflictingClassGroupModifiers { get; }

    /**
     * Modifiers whose order among multiple modifiers should be preserved because their order changes which element gets targeted.
     *
     * tailwind-merge makes sure that classes with these modifiers are not overwritten by classes with the same modifiers with order-sensitive modifiers being in a different position.
     *
     * @var array<string>
     */
    public array $orderSensitiveModifiers { get; }

    /**
     * @param  array<TClassGroupIds, ClassGroup>  $classGroups
     * @return ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
     */
    public function setClassGroups(array $classGroups): ConfigGroupPart;

    /**
     * @param  ThemeObject  $theme
     * @return ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
     */
    public function setTheme(array $theme): ConfigGroupPart;

    /**
     * @param  array<TClassGroupIds, array<TClassGroupIds>>  $conflictingClassGroups
     * @return ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
     */
    public function setConflictingClassGroups(array $conflictingClassGroups): ConfigGroupPart;

    /**
     * @param  array<TClassGroupIds, array<TClassGroupIds>>  $conflictingClassGroupModifiers
     * @return ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
     */
    public function setConflictingClassGroupModifiers(array $conflictingClassGroupModifiers): ConfigGroupPart;

    /**
     * @param  array<string>  $orderSensitiveModifiers
     * @return ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
     */
    public function setOrderSensitiveModifiers(array $orderSensitiveModifiers): ConfigGroupPart;
}
