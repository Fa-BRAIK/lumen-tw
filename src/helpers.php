<?php

declare(strict_types=1);

use Lumen\TwMerge\Facades\TwMerge;
use Lumen\TwMerge\Support\CssClassBuilder;

if ( ! function_exists('tw_merge')) {
    /**
     * Merge Tailwind CSS classes.
     *
     * @param  array<string>|string|null  $classes
     */
    function tw_merge(array|string|null ...$classes): string
    {
        return TwMerge::merge(...$classes);
    }
}

if ( ! function_exists('css_classes_builder')) {
    /**
     * Create a new instance of CssClassBuilder.
     *
     * @param  array<string>|string|null  $classes
     */
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
