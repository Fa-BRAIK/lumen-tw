<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsAnyValidator extends Validator
{
    public function __invoke(string $className = ''): true
    {
        return true;
    }
}
