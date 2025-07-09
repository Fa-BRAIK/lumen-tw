<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\ValueObjects;

class ParsedClass
{
    /**
     * @param  array<array-key, string>  $modifiers
     */
    public function __construct(
        public array $modifiers,
        public bool $hasImportantModifier,
        public bool $hasPostfixModifier,
        public string $modifierId,
        public string $classGroupId,
        public string $baseClassName,
        public string $originalClassName,
        public bool $isExternal = false
    ) {}
}
