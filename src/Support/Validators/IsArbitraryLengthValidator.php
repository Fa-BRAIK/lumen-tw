<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryLengthValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return $this->getIsArbitraryValue(
            value: $className,
            testLabel: $this->isLabelClosure('isLabelLength'),
            testValue: resolve(IsLengthOnlyValidator::class)
        );
    }
}
