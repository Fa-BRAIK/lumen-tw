<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsFractionValidator extends RegexValidator
{
    /**
     * @inheritDoc
     */
    public function patterns(): string
    {
        return self::FRACTION_PATTERN;
    }
}
