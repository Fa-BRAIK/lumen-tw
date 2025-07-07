<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryVariablePositionValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return $this->getIsArbitraryVariable(
            value: $className,
            testLabel: $this->isLabelClosure('isLabelPosition'),
        );
    }
}
