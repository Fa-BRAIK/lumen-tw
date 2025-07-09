<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\ValueObjects;

class ClassPartObject
{
    /**
     * @param  array<array-key, ClassPartObject>  $nextPart
     * @param  array<array-key, \Lumen\TwMerge\Support\Contracts\ClassValidator>  $validators
     */
    public function __construct(
        protected(set) array $nextPart = [],
        protected(set) array $validators = [],
        protected(set) ?string $classGroupId = null,
    ) {}
}
