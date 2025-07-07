<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryValueValidator extends RegexValidator
{
    public string $patterns {
        get => self::ARBITRARY_VALUE_PATTERN;
    }
}
