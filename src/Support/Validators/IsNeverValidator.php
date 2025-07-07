<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsNeverValidator extends Validator
{
    public function __invoke(string $className): false
    {
        return false;
    }
}
