<?php

declare(strict_types=1);

if ( ! function_exists('twMerge')) {
    /**
     * Merge Tailwind CSS classes.
     *
     * @param  array<string>|string|null  $classes
     */
    function twMerge(array|string|null ...$classes): string
    {
        return app('twMerge')->merge(...$classes);
    }
}
