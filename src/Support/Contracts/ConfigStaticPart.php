<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

interface ConfigStaticPart
{
    /**
     * @deprecated After port, I didn't find any purpose for this property. It will be removed in the future... Unless a use case is found.
     */
    public function getCacheSize(): ?int;

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/configuration#prefix
     */
    public function getPrefix(): ?string;

    public function setCacheSize(?int $cacheSize): ConfigStaticPart|static;

    public function setPrefix(?string $prefix): ConfigStaticPart|static;
}
