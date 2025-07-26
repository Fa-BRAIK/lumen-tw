<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsTshirtSizeValidator extends RegexValidator
{
    /**
     * {@inheritDoc}
     */
    public function patterns(): string
    {
        return self::TSHIRT_UNIT_PATTERN;
    }
}
