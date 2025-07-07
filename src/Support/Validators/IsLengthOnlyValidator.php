<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsLengthOnlyValidator extends RegexValidator
{
    public string $patterns {
        get => self::LENGTH_UNIT_PATTERN;
    }

    public string $excludes {
        get => self::COLOR_FUNCTION_PATTERN;
    }
}
