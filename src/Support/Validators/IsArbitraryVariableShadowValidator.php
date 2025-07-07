<?php

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryVariableShadowValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return $this->getIsArbitraryVariable(
            value: $className,
            testLabel: $this->isLabelClosure('isLabelShadow'),
            shouldMatchNoLabel: true
        );
    }
}
