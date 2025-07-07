<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsIntegerValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return (bool) filter_var($className, FILTER_VALIDATE_INT);
    }
}
