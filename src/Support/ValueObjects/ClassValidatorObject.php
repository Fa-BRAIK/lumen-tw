<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\ValueObjects;

use Lumen\TwMerge\Support\Contracts\ClassValidator;

class ClassValidatorObject
{
    public function __construct(
        protected(set) string $classGroupId,
        protected(set) ClassValidator $validator,
    ) {}
}
