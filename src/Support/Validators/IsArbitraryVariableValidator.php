<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryVariableValidator extends RegexValidator
{
    public string $patterns {
        get => self::ARBITRARY_VARIABLE_PATTERN;
    }
}
