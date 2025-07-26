<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsArbitraryValueValidator extends RegexValidator
{
    /**
     * {@inheritDoc}
     */
    public function patterns(): string
    {
        return self::ARBITRARY_VALUE_PATTERN;
    }
}
