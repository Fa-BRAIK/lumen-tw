<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can merge standalone classes from same group correctly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['inline block', 'block'],
    ['hover:block hover:inline', 'hover:inline'],
    ['hover:block hover:block', 'hover:block'],
    ['inline hover:inline focus:inline hover:block hover:focus:block', 'inline focus:inline hover:block hover:focus:block'],
    ['underline line-through', 'line-through'],
    ['line-through no-underline', 'no-underline'],
]);
