<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

interface ConfigStaticPart
{
    public ?int $cacheSize { get; }

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/configuration#prefix
     */
    public ?string $prefix { get; }

    public function setCacheSize(?int $cacheSize): ConfigStaticPart|static;

    public function setPrefix(?string $prefix): ConfigStaticPart|static;
}
