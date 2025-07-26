<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

use Override;

final class IsLengthOnlyValidator extends RegexValidator
{
    /**
     * {@inheritDoc}
     */
    public function patterns(): string
    {
        return self::LENGTH_UNIT_PATTERN;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function excludes(): string
    {
        return self::COLOR_FUNCTION_PATTERN;
    }
}
