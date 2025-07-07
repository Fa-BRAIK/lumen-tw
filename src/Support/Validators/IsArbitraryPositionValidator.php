<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryPositionValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return $this->getIsArbitraryValue(
            value: $className,
            testLabel: $this->isLabelClosure('isLabelPosition'),
            testValue: resolve(IsNeverValidator::class)
        );
    }
}
