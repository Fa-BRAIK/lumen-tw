<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsImageValidator extends RegexValidator
{
    public string $patterns {
        get => self::IMAGE_PATTERN;
    }
}
