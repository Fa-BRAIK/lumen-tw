<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lumen\TwMerge\Support\Contracts\Config as ConfigContract;

/**
 * @phpstan-import-type AnyConfig from ConfigContract
 *
 * @phpstan-type CreateParseClassNameClosure = Closure(string): ParsedClassName
 * @phpstan-type CreateSortModifiersClosure = Closure(array<string>): array<string>
 *
 * @phpstan-consistent-constructor
 */
class ParsedClassName
{
    public const string IMPORTANT_MODIFIER = '!';

    public const string MODIFIER_SEPARATOR = ':';

    public function __construct(
        /**
         * Whether the class is external and merging logic should be sipped.
         *
         * If this is `true`, the class will be treated as if it wasn't a Tailwind class and will be passed through as is.
         */
        protected ?bool $isExternal = null,

        /**
         * Modifiers of the class in the order they appear in the class.
         *
         * @example ['hover', 'dark'] // for `hover:dark:bg-gray-100`
         *
         * @var array<string>
         */
        protected array $modifiers = [],

        /**
         * Whether the class has an `!important` modifier.
         *
         * @example true // for `hover:dark:!bg-gray-100`
         */
        protected ?bool $hasImportantModifier = null,

        /**
         * Base class without preceding modifiers.
         *
         * @example 'bg-gray-100' // for `hover:dark:bg-gray-100`
         */
        protected string $baseClassName = '',

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
        protected ?int $maybePostfixModifierPosition = null,
    ) {}

    public function isItExternal(): ?bool
    {
        return $this->isExternal;
    }

    /**
     * @return array<string>
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function doesItHaveImportantModifier(): bool
    {
        return $this->hasImportantModifier ?? false;
    }

    public function getBaseClassName(): string
    {
        return $this->baseClassName;
    }

    public function getMaybePostfixModifierPosition(): ?int
    {
        return $this->maybePostfixModifierPosition;
    }

    /**
     * @param  AnyConfig  $config
     * @return CreateParseClassNameClosure
     */
    public static function createParseClassName(ConfigContract $config): Closure
    {
        /**
         * Parse class name into parts.
         *
         * Inspired by `splitAtTopLevelOnly` used in Tailwind CSS
         *
         * @see https://github.com/tailwindlabs/tailwindcss/blob/v3.2.2/src/util/splitAtTopLevelOnly.js
         */
        $parseClassName = function (string $className): ParsedClassName {
            $modifiers = [];

            $bracketDepth = 0;
            $parenDepth = 0;
            $modifierStart = 0;
            /** @var ?int $postfixModifierPosition */
            $postfixModifierPosition = null;

            for ($index = 0; $index < Str::length($className); $index++) {
                $currentCharacter = $className[$index];

                if (0 === $bracketDepth && 0 === $parenDepth) {
                    if (static::MODIFIER_SEPARATOR === $currentCharacter) {
                        $modifiers[] = Str::substr($className, $modifierStart, $index - $modifierStart);
                        $modifierStart = $index + Str::length(static::MODIFIER_SEPARATOR);

                        continue;
                    }

                    if ('/' === $currentCharacter) {
                        $postfixModifierPosition = $index;

                        continue;
                    }
                }

                if ('[' === $currentCharacter) {
                    $bracketDepth++;
                } elseif (']' === $currentCharacter) {
                    $bracketDepth--;
                } elseif ('(' === $currentCharacter) {
                    $parenDepth++;
                } elseif (')' === $currentCharacter) {
                    $parenDepth--;
                }
            }

            $baseClassNameWithImportantModifier = ! $className
                ? $className
                : Str::substr($className, $modifierStart);

            $baseClassName = static::stripImportantModifier($baseClassNameWithImportantModifier);
            $hasImportantModifier = $baseClassName !== $baseClassNameWithImportantModifier;
            $maybePostfixModifierPosition = $postfixModifierPosition && $postfixModifierPosition > $modifierStart
                ? $postfixModifierPosition - $modifierStart
                : null;

            return new static(
                modifiers: $modifiers,
                hasImportantModifier: $hasImportantModifier,
                baseClassName: $baseClassName,
                maybePostfixModifierPosition: $maybePostfixModifierPosition
            );
        };

        if ($config->prefix) {
            $fullPrefix = $config->prefix . self::MODIFIER_SEPARATOR;
            $parseClassNameOriginal = $parseClassName;
            $parseClassName = fn (string $className) => Str::startsWith($className, $fullPrefix)
                ? $parseClassNameOriginal(Str::substr($className, Str::length($fullPrefix)))
                : new self(
                    isExternal: true,
                    hasImportantModifier: false,
                    baseClassName: $className,
                );
        }

        return $parseClassName;
    }

    /**
     * Sorts modifiers according to following schema:
     * - Predefined modifiers are sorted alphabetically
     * - When an arbitrary variant appears, it must be preserved which modifiers are before and after it
     *
     * @param  AnyConfig  $config
     * @return CreateSortModifiersClosure
     */
    public static function createSortModifiers(ConfigContract $config): Closure
    {
        $orderSensitiveModifiers = Arr::mapWithKeys(
            $config->orderSensitiveModifiers,
            static fn (string $modifier) => [$modifier => true]
        );

        /**
         * @param  array<string>  $modifiers
         * @return array<string>
         *
         * Another limitation of PHPStan, Docblock are ignored for closures/anonymouse functions.
         * https://github.com/phpstan/phpstan/issues/3770
         */
        return function (array $modifiers) use ($orderSensitiveModifiers): array { // @phpstan-ignore-line
            if (count($modifiers) <= 1) {
                return $modifiers;
            }

            /** @var array<string> $sortedModifiers */
            $sortedModifiers = [];
            /** @var array<string> $unsortedModifiers */
            $unsortedModifiers = [];

            foreach ($modifiers as $modifier) {
                /** @var string $modifier */
                $isPositionSensitive = '[' === Str::charAt($modifier, 0)
                    || ($orderSensitiveModifiers[$modifier] ?? false);

                if ($isPositionSensitive) {
                    $sortedModifiers = [...$sortedModifiers, ...Arr::sort($unsortedModifiers), $modifier];
                    $unsortedModifiers = [];
                } else {
                    $unsortedModifiers[] = $modifier;
                }
            }

            return [
                ...$sortedModifiers,
                ...Arr::sort($unsortedModifiers),
            ];
        };
    }

    protected static function stripImportantModifier(string $baseClassName): string
    {
        if (Str::endsWith($baseClassName, static::IMPORTANT_MODIFIER)) {
            return Str::substr($baseClassName, 0, -1);
        }

        /**
         * In Tailwind CSS v3 the important modifier was at the start of the base class name. This is still supported for legacy reasons.
         *
         * @see https://github.com/dcastil/tailwind-merge/issues/513#issuecomment-2614029864
         */
        if (Str::startsWith($baseClassName, static::IMPORTANT_MODIFIER)) {
            return Str::substr($baseClassName, 1);
        }

        return $baseClassName;
    }
}
