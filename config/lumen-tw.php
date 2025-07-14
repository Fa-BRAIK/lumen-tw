<?php

declare(strict_types=1);

return [
    /**
     * Integer indicating size cache used for memorising results.
     */
    'cache_size' => env('TW_CACHE_SIZE', 500),

    /**
     * Cache store used, defaults to application's default cache store.
     * Using false will disable caching.
     */
    'cache_store' => env('TW_CACHE_STORE'),

    /**
     * Prefix used for cache keys.
     */
    'cache_prefix' => env('TW_CACHE_PREFIX', 'tw_merge'),

    /**
     * Prefix added to Tailwind-generated classes
     *
     * @see https://tailwindcss.com/docs/styling-with-utility-classes#using-the-prefix-option
     */
    'prefix' => env('TW_PREFIX', ''),

    /**
     * Blade directive name, defaults to 'twMerge'.
     *
     * Setting this to `false` or `null` will disable the directive.
     */
    'tw_merge_directive' => env('TW_DIRECTIVE', 'twMerge'),

    /**
     * Component attribute bag macro name, defaults to 'twMerge'.
     *
     * Setting this to `false` or `null` will disable the macro.
     */
    'tw_merge_macro' => env('TW_MERGE_MACRO_NAME', 'twMerge'),

    /**
     * Macro for merging Tailwind classes in a component attribute bag.
     *
     * This is used when the `twMergeFor` macro is called on a ComponentAttributeBag.
     * Setting this to `false` or `null` will disable the macro.
     */
    'tw_merge_for_macro' => env('TW_MACRO_FOR_MACRO_NAME', 'twMergeFor'),

    /**
     * Macro for merging Tailwind classes without using the twMerge function.
     * This is useful for cases where you want to merge classes without the twMerge function.
     *
     * Setting this to `false` or `null` will disable the macro.
     */
    'without_tw_merge_classes_macro' => env('TW_WITHOUT_TW_MERGE_CLASSES_NAME', 'withoutTwMergeClasses'),
];
