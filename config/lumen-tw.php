<?php

declare(strict_types=1);

return [
    /**
     * Integer indicating size cache used for memoizing results.
     */
    'cache_size' => env('LUMEN_TW_CACHE_SIZE', 500),

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/styling-with-utility-classes#using-the-prefix-option
     */
    'prefix' => env('LUMEN_TW_PREFIX', null),
];
