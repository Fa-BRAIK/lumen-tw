<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('merges non-conflicting classes correctly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['border-t border-white/10', 'border-t border-white/10'],
    ['border-t border-white', 'border-t border-white'],
    ['text-3.5xl text-black', 'text-3.5xl text-black'],
]);
