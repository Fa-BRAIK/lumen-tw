<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

use Lumen\TwMerge\Support\Validators\RegexValidator;

final class IsFractionValidator extends RegexValidator
{
    public string $patterns {
        get => self::FRACTION_PATTERN;
    }
}
