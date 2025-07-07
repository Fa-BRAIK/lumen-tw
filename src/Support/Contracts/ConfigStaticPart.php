<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * The static part of the tailwind-merge configuration. When merging multiple configurations, the properties of this interface are always overridden.
 */
interface ConfigStaticPart
{
    /**
     * Integer indicating size of LRU cache used for memoizing results.
     * - Cache might be up to twice as big as `cacheSize`
     * - No cache is used for values <= 0
     */
    public int $cacheSize { get; }

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/configuration#prefix
     */
    public ?string $prefix { get; }

    /**
     * Allows to customize parsing of individual classes passed to `twMerge`.
     * All classes passed to `twMerge` outside of cache hits are passed to this function before it is determined whether the class is a valid Tailwind CSS class.
     *
     * This is an experimental feature and may introduce breaking changes in any minor version update.
     */
    public function experimentalParseClassName(ExperimentalParseClassNameParam $param): ParsedClassName;
}
