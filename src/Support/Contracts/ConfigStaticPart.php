<?php

namespace Lumen\TwMerge\Support\Contracts;

interface ConfigStaticPart
{
    public ?int $cacheSize { get; }

    /**
     * Prefix added to Tailwind-generated classes
     * @see https://tailwindcss.com/docs/configuration#prefix
     */
    public ?string $prefix { get; }
}
