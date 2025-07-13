<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\CssClassBuilder;

if ( ! function_exists('tw_merge')) {
    /**
     * Merge Tailwind CSS classes.
     *
     * @param  array<string>|string|null  $classes
     */
    function tw_merge(array|string|null ...$classes): string
    {
        return app('twMerge')->merge(...$classes);
    }
}

if ( ! function_exists('css_classes_builder')) {
    function css_classes_builder(array|string|null ...$classes): CssClassBuilder
    {
        return new CssClassBuilder(...$classes);
    }
}

if ( ! function_exists('build_css_classes')) {
    /**
     * Build CSS classes from the given input.
     *
     * @param  array<string>|string|null  $classes
     */
    function build_css_classes(array|string|null ...$classes): string
    {
        return CssClassBuilder::staticBuild(...$classes);
    }
}
