<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Lumen\TwMerge\Support\Contracts\ConfigGroupPart;

/**
 * @phpstan-import-type ThemeObject from ConfigGroupPart
 * @phpstan-import-type ClassGroup from ConfigGroupPart
 */
final readonly class ThemeGetter
{
    public function __construct(public string $key) {}

    /**
     * @param  'animate'|'aspect'|'blur'|'breakpoint'|'color'|'container'|'drop-shadow'|'ease'|'font-weight'|'font'|'inset-shadow'|'leading'|'perspective'|'radius'|'shadow'|'spacing'|'text'|'text-shadow'|'tracking'  $key
     */
    public static function fromTheme(string $key): self
    {
        return new self($key);
    }

    /**
     * @param  ThemeObject  $theme
     * @return ClassGroup
     */
    public function get(array $theme): array
    {
        return $theme[$this->key] ?? [];
    }
}
