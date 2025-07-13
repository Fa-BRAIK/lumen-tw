<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Lumen\TwMerge\Support\Contracts\CssClassBuilder as CssClassBuilderContract;

/**
 * @phpstan-import-type ClassNameValue from CssClassBuilderContract
 */
class CssClassBuilder implements CssClassBuilderContract
{
    /**
     * @var Collection<array-key, ClassNameValue>
     */
    public protected(set) Collection $classes;

    public function __construct(array|string|null ...$classes)
    {
        $this->classes = collect($classes);
    }

    public function __toString(): string
    {
        return $this->build();
    }

    public static function staticBuild(string|array|null ...$classes): string
    {
        return static::buildCollection(collect($classes));
    }

    /**
     * @param  ClassNameValue  ...$classes
     */
    public function add(array|string|null ...$classes): self
    {
        $this->classes = $this->classes->push(...$classes);

        return $this;
    }

    /**
     * @param  bool|Closure():bool  $condition
     * @param  ClassNameValue  ...$classes
     */
    public function addIf(bool|Closure $condition, array|string|null ...$classes): self
    {
        if (value($condition)) {
            $this->classes = $this->classes->push(...$classes);
        }

        return $this;
    }

    public function build(): string
    {
        return static::buildCollection($this->classes);
    }

    /**
     * @param  Collection<array-key, ClassNameValue>  $classes
     */
    protected static function buildCollection(Collection $classes): string
    {
        return $classes
            ->map(static::convertToString(...))
            ->filter()
            ->join(' ');
    }

    /**
     * @param  ClassNameValue  $mix
     */
    protected static function convertToString(array|string|null $mix): string
    {
        if (null === $mix) {
            return '';
        }

        if (is_string($mix)) {
            return $mix;
        }

        $strings = [];

        foreach ($mix as $subMix) {
            if ($resolvedValue = static::convertToString($subMix)) {
                $strings[] = $resolvedValue;
            }
        }

        return Arr::toCssClasses($strings);
    }
}
