<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can handle basic arbitrary variants', function (): void {
    expect(app('twMerge')->merge('[p]:underline [p]:line-through'))
        ->toBe('[p]:line-through')
        ->and(app('twMerge')->merge('[&>*]:underline [&>*]:line-through'))
        ->toBe('[&>*]:line-through')
        ->and(app('twMerge')->merge('[&>*]:underline [&>*]:line-through [&_div]:line-through'))
        ->toBe('[&>*]:line-through [&_div]:line-through')
        ->and(app('twMerge')->merge('supports-[display:grid]:flex supports-[display:grid]:grid'))
        ->toBe('supports-[display:grid]:grid');
});

it('can handle arbitrary variants with modifiers', function (): void {
    expect(app('twMerge')->merge('dark:lg:hover:[&>*]:underline dark:lg:hover:[&>*]:line-through'))
        ->toBe('dark:lg:hover:[&>*]:line-through')
        ->and(app('twMerge')->merge('dark:lg:hover:[&>*]:underline dark:hover:lg:[&>*]:line-through'))
        ->toBe('dark:hover:lg:[&>*]:line-through')

    // Whether a modifier is before or after arbitrary variant matters
        ->and(app('twMerge')->merge('hover:[&>*]:underline [&>*]:hover:line-through'))
        ->toBe('hover:[&>*]:underline [&>*]:hover:line-through')
        ->and(app('twMerge')->merge('hover:dark:[&>*]:underline dark:hover:[&>*]:underline dark:[&>*]:hover:line-through'))
        ->toBe('dark:hover:[&>*]:underline dark:[&>*]:hover:line-through');
});

it('can handle arbitrary variants with complex syntax', function (): void {
    expect(app('twMerge')->merge('[@media_screen{@media(hover:hover)}]:underline [@media_screen{@media(hover:hover)}]:line-through'))
        ->toBe('[@media_screen{@media(hover:hover)}]:line-through')
        ->and(app('twMerge')->merge('hover:[@media_screen{@media(hover:hover)}]:underline hover:[@media_screen{@media(hover:hover)}]:line-through'))
        ->toBe('hover:[@media_screen{@media(hover:hover)}]:line-through');
});

it('can handle arbitrary variants with complex syntax in them', function (): void {
    expect(app('twMerge')->merge('[@media_screen{@media(hover:hover)}]:underline [@media_screen{@media(hover:hover)}]:line-through'))
        ->toBe('[@media_screen{@media(hover:hover)}]:line-through')
        ->and(app('twMerge')->merge('hover:[@media_screen{@media(hover:hover)}]:underline hover:[@media_screen{@media(hover:hover)}]:line-through'))
        ->toBe('hover:[@media_screen{@media(hover:hover)}]:line-through');
});

it('can handle arbitrary variants with attribute selectors', function (): void {
    expect(app('twMerge')->merge('[&[data-open]]:underline [&[data-open]]:line-through'))
        ->toBe('[&[data-open]]:line-through');
});

it('can handle arbitrary variants with multiple attribute selectors', function (): void {
    expect(app('twMerge')->merge('[&[data-foo][data-bar]:not([data-baz])]:underline [&[data-foo][data-bar]:not([data-baz])]:line-through'))
        ->toBe('[&[data-foo][data-bar]:not([data-baz])]:line-through');
});

it('can handle multiple arbitrary variants', function (): void {
    expect(app('twMerge')->merge('[&>*]:[&_div]:underline [&>*]:[&_div]:line-through'))
        ->toBe('[&>*]:[&_div]:line-through')
        ->and(app('twMerge')->merge('[&>*]:[&_div]:underline [&_div]:[&>*]:line-through'))
        ->toBe('[&>*]:[&_div]:underline [&_div]:[&>*]:line-through')
        ->and(app('twMerge')->merge('hover:dark:[&>*]:focus:disabled:[&_div]:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through'))
        ->toBe('dark:hover:[&>*]:disabled:focus:[&_div]:line-through')
        ->and(app('twMerge')->merge('hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through'))
        ->toBe('hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through');
});

it('can handle arbitrary variants with arbitrary properties', function (): void {
    expect(app('twMerge')->merge('[&>*]:[color:red] [&>*]:[color:blue]'))
        ->toBe('[&>*]:[color:blue]')
        ->and(app('twMerge')->merge('[&[data-foo][data-bar]:not([data-baz])]:nod:noa:[color:red] [&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]'))
        ->toBe('[&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]');
});
