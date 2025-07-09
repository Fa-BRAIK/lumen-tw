<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\ValueObjects;

class ThemeGetter
{
    public function __construct(
        protected(set) string $key,
    ) {}

    /**
     * @param  'animate'|'aspect'|'blur'|'breakpoint'|'color'|'container'|'drop-shadow'|'ease'|'font-weight'|'font'|'inset-shadow'|'leading'|'perspective'|'radius'|'shadow'|'spacing'|'text'|'text-shadow'|'tracking'  $key
     */
    public static function fromTheme(string $key): static
    {
        return new static($key);
    }

    /**
     * @param  array<string, array<string, mixed>>  $theme
     * @return array<string, mixed>
     */
    public function get(array $theme): array
    {
        return $theme[$this->key] ?? [];
    }
}
