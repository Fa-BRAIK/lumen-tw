<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsNumberValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return is_numeric($className);
    }
}
