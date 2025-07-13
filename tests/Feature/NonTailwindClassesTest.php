<?php

declare(strict_types=1);

it('does not alter non-tailwind classes', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['non-tailwind-class inline block', 'non-tailwind-class block'],
    ['inline block inline-1', 'block inline-1'],
    ['inline block i-inline', 'block i-inline'],
    ['focus:inline focus:block focus:inline-1', 'focus:block focus:inline-1'],
]);
