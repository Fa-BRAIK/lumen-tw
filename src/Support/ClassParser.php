<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lumen\TwMerge\Support\ValueObjects\ClassPartObject;
use Lumen\TwMerge\Support\ValueObjects\ClassValidatorObject;
use Lumen\TwMerge\Support\ValueObjects\ParsedClass;

class ClassParser
{
    public const string CLASS_PART_SEPARATOR = '-';

    public const string ARBITRARY_PROPERTY_REGEX = '/^\[(.+)]$/';

    public const string IMPORTANT_MODIFIER = '!';

    public const string MODIFIER_SEPARATOR = ':';

    /**
     * @var list<string>
     */
    public const array POSITION_SENSITIVE_MODIFIERS = [
        'before',
        'after',
        'placeholder',
        'file',
        'marker',
        'selection',
        'first-line',
        'first-letter',
        'backdrop',
        '*',
        '**',
    ];

    public protected(set) ?string $prefix = 'tw';

    public protected(set) ClassPartObject $classMap;

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
    public function __construct(public array $config)
    {
        $this->prefix = $config['prefix'] ?? $this->prefix;
        $this->classMap = ClassMap::fromConfig($config);
    }

    public function parse(string $class): ParsedClass
    {
        $originalClass = $class;

        if ($this->prefix) {
            $fullPrefix = $this->prefix . self::MODIFIER_SEPARATOR;
            if (str_contains($class, $fullPrefix)) {
                $class = str_replace($fullPrefix, '', $class);
            } else {
                return new ParsedClass(
                    modifiers: [],
                    hasImportantModifier: false,
                    hasPostfixModifier: false,
                    modifierId: '',
                    classGroupId: '',
                    baseClassName: '',
                    originalClassName: $originalClass,
                    isExternal: true
                );
            }
        }

        [
            'modifiers' => $modifiers,
            'hasImportantModifier' => $hasImportantModifier,
            'baseClassName' => $baseClassName,
            'maybePostfixModifierPosition' => $maybePostfixModifierPosition
        ] = $this->splitModifiers($class);

        $classGroupId = $this->getClassGroupId($maybePostfixModifierPosition ? Str::substr($baseClassName, 0, $maybePostfixModifierPosition) : $baseClassName);

        $hasPostfixModifier = null !== $maybePostfixModifierPosition;
        $variantModifier = implode(':', $this->sortModifiers($modifiers));
        $modifierId = $hasImportantModifier ? $variantModifier . self::IMPORTANT_MODIFIER : $variantModifier;

        return new ParsedClass(
            modifiers: $modifiers,
            hasImportantModifier: $hasImportantModifier,
            hasPostfixModifier: $hasPostfixModifier,
            modifierId: $modifierId,
            classGroupId: $classGroupId,
            baseClassName: $baseClassName,
            originalClassName: $originalClass,
        );
    }

    public function stripImportantModifier(string $baseClassName): string
    {
        if (str_ends_with($baseClassName, self::IMPORTANT_MODIFIER)) {
            return mb_substr($baseClassName, 0, -1);
        }

        // Legacy: important modifier at the start
        if (str_starts_with($baseClassName, self::IMPORTANT_MODIFIER)) {
            return mb_substr($baseClassName, 1);
        }

        return $baseClassName;
    }

    /**
     * @param  array<array-key, string>  $classParts
     */
    protected static function getGroupRecursive(array $classParts, ClassPartObject $classPartObject): ?string
    {
        if ([] === $classParts) {
            return $classPartObject->classGroupId;
        }

        $currentClassPart = $classParts[0] ?? null;
        $nextClassPartObject = $classPartObject->nextParts[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject instanceof ClassPartObject
            ? self::getGroupRecursive(
                array_slice($classParts, 1),
                $nextClassPartObject
            )
            : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if ([] === $classPartObject->validators) {
            return null;
        }

        $classRest = implode(self::CLASS_PART_SEPARATOR, $classParts);

        return Arr::first(
            $classPartObject->validators,
            static fn (ClassValidatorObject $validatorObject) => ($validatorObject->validator)($classRest)
        )?->classGroupId;
    }

    protected function getClassGroupId(string $class): string
    {
        $classParts = explode(self::CLASS_PART_SEPARATOR, $class);

        // Classes like `-inset-1` produce an empty string as first classPart.
        // We assume that classes for negative values are used correctly and remove it from classParts.
        if ('' === $classParts[0] && 1 !== count($classParts)) {
            array_shift($classParts);
        }

        return self::getGroupRecursive($classParts, $this->classMap)
            ?: $this->getGroupIdForArbitraryProperty($class);
    }

    /**
     * @return array{
     *     modifiers: array<array-key, string>,
     *     hasImportantModifier: bool,
     *     baseClassName: string,
     *     maybePostfixModifierPosition: int|null
     * }
     */
    protected function splitModifiers(string $className): array
    {
        $separatorConfig = app('twMerge')->getMergedConfig()['separator'] ?? null;
        $separator = is_string($separatorConfig) ? $separatorConfig : ':';

        $isSeparatorSingleCharacter = 1 === mb_strlen($separator);
        $firstSeparatorCharacter = $separator[0];
        $separatorLength = mb_strlen($separator);

        $modifiers = [];

        $parentDepth = 0;
        $bracketDepth = 0;
        $modifierStart = 0;
        $postfixModifierPosition = null;

        for ($index = 0; $index < mb_strlen($className); $index++) {
            $currentCharacter = $className[$index];

            if (0 === $bracketDepth && 0 === $parentDepth) {
                if (
                    $currentCharacter === $firstSeparatorCharacter &&
                    ($isSeparatorSingleCharacter
                    || Str::substr($className, $index, $separatorLength) === $separator)
                ) {
                    $modifiers[] = Str::substr($className, $modifierStart, $index - $modifierStart);
                    $modifierStart = $index + $separatorLength;

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
                $parentDepth++;
            } elseif (')' === $currentCharacter) {
                $parentDepth--;
            }
        }

        $baseClassNameWithImportantModifier =
            [] === $modifiers ? $className : Str::substr($className, $modifierStart);
        $baseClassName = $this->stripImportantModifier($baseClassNameWithImportantModifier);
        $hasImportantModifier = $baseClassName !== $baseClassNameWithImportantModifier;

        $maybePostfixModifierPosition = $postfixModifierPosition && $postfixModifierPosition > $modifierStart
            ? $postfixModifierPosition - $modifierStart
            : null;

        return [
            'modifiers' => $modifiers,
            'hasImportantModifier' => $hasImportantModifier,
            'baseClassName' => $baseClassName,
            'maybePostfixModifierPosition' => $maybePostfixModifierPosition,
        ];
    }

    /**
     * @param  array<array-key, string>  $modifiers
     * @return array<array-key, string>
     */
    protected function sortModifiers(array $modifiers): array
    {
        if (count($modifiers) <= 1) {
            return $modifiers;
        }

        $sortedModifiers = collect($modifiers);

        /** @var Collection<array-key, string> $unsortedModifiers */
        $unsortedModifiers = collect();

        foreach ($modifiers as $modifier) {
            $isPositionSensitive = '[' === $modifier[0] || isset(self::POSITION_SENSITIVE_MODIFIERS[$modifier]);

            if ($isPositionSensitive) {
                $sortedModifiers = $sortedModifiers->concat([...$unsortedModifiers->sort()->all(), $modifier]);

                /** @var Collection<array-key, string> $unsortedModifiers */
                $unsortedModifiers = collect();
            } else {
                $unsortedModifiers->add($modifier);
            }
        }

        $sortedModifiers = $sortedModifiers->concat($unsortedModifiers->sort()->all());

        return $sortedModifiers->all();
    }

    private function getGroupIdForArbitraryProperty(string $className): string
    {
        if (
            '' !== Str::match(self::ARBITRARY_PROPERTY_REGEX, $className)
            && '0' !== Str::match(self::ARBITRARY_PROPERTY_REGEX, $className)
        ) {
            $arbitraryPropertyClassName = Str::match(self::ARBITRARY_PROPERTY_REGEX, $className);
            $property = Str::before($arbitraryPropertyClassName, ':');

            if ('' !== $property && '0' !== $property) {
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..' . $property;
            }
        }

        return $className;
    }
}
