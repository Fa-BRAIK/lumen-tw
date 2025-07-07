<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsTshirtSizeValidator extends RegexValidator
{
    public string $patterns {
        get => self::TSHIRT_UNIT_PATTERN;
    }
}
