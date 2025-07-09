<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsFractionValidator extends RegexValidator
{
    public string $patterns {
        get => self::FRACTION_PATTERN;
    }
}
