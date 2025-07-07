<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * Type of the result returned by the `experimentalParseClassName` function.
 *
 * This is an experimental feature and may introduce breaking changes in any minor version update.
 */
interface ParsedClassName
{
    /**
     * Whether the class is external and merging logic should be sipped.
     *
     * If this is `true`, the class will be treated as if it wasn't a Tailwind class and will be passed through as is.
     */
    public ?bool $isValid { get; }

    /**
     * Modifiers of the class in the order they appear in the class.
     *
     * @example ['hover', 'dark'] // for `hover:dark:bg-gray-100`
     *
     * @var array<string>
     */
    public array $modifiers { get; }

    /**
     * Whether the class has an `!important` modifier.
     *
     * @example true // for `hover:dark:!bg-gray-100`
     */
    public bool $hasImportantModifier { get; }

    /**
     * Base class without preceding modifiers.
     *
     * @example 'bg-gray-100' // for `hover:dark:bg-gray-100`
     */
    public string $baseClassName { get; }

    /**
     * Index position of a possible postfix modifier in the class.
     * If the class has no postfix modifier, this is `undefined`.
     *
     * This property is prefixed with "maybe" because tailwind-merge does not know whether something is a postfix modifier or part of the base class since it's possible to configure Tailwind CSS classes which include a `/` in the base class name.
     *
     * If a `maybePostfixModifierPosition` is present, tailwind-merge first tries to match the `baseClassName` without the possible postfix modifier to a class group. If that fails, it tries again with the possible postfix modifier.
     *
     * @example 11 // for `bg-gray-100/50`
     */
    public ?int $maybePostfixModifierPosition { get; }
}
