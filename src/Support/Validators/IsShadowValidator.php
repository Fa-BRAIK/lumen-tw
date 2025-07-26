<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsShadowValidator extends RegexValidator
{
    /**
     * @inheritDoc
     */
    public function patterns(): string
    {
        return self::SHADOW_PATTERN;
    }
}
