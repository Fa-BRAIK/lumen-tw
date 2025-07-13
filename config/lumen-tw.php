<?php

declare(strict_types=1);

return [
    /**
     * Integer indicating size cache used for memorising results.
     */
    'cache_size' => env('LUMEN_TW_CACHE_SIZE', 500),

    /**
     * Cache store used, defaults to application's default cache store.
     * Using false will disable caching.
     */
    'cache_store' => env('LUMEN_TW_CACHE_STORE'),

    /**
     * Prefix used for cache keys.
     */
    'cache_prefix' => env('LUMEN_TW_CACHE_PREFIX', 'tw_merge'),

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/styling-with-utility-classes#using-the-prefix-option
     */
    'prefix' => env('LUMEN_TW_PREFIX', ''),

    /**
     * Blade directive name, defaults to 'twMerge'.
     *
     * Setting this to `false` or `null` will disable the directive.
     */
    'tw_merge_directive' => env('LUMEN_TW_DIRECTIVE', 'twMerge'),

    /**
     * Component attribute bag macro name, defaults to 'twMerge'.
     *
     * Setting this to `false` or `null` will disable the macro.
     */
    'tw_merge_macro' => env('LUMEN_TW_MACRO', 'twMerge'),
];
