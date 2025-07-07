<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsAnyNonArbitrary extends Validator
{
    public function __invoke(string $className): bool
    {
        return ! resolve(IsArbitraryValueValidator::class)($className)
            && ! resolve(IsArbitraryVariableValidator::class)($className);
    }
}
