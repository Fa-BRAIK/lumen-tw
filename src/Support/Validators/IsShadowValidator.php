<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

final class IsShadowValidator extends RegexValidator
{
    public string $patterns {
        get => self::SHADOW_PATTERN;
    }
}
