<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsImageValidator extends RegexValidator
{
    public function patterns(): string
    {
        return self::IMAGE_PATTERN;
    }
}
