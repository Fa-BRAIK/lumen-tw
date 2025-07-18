<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('handles pseudo variants conflicts properly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['empty:p-2 empty:p-3', 'empty:p-3'],
    ['hover:empty:p-2 hover:empty:p-3', 'hover:empty:p-3'],
    ['read-only:p-2 read-only:p-3', 'read-only:p-3'],
]);

it('handles pseudo variant group conflicts properly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['group-empty:p-2 group-empty:p-3', 'group-empty:p-3'],
    ['peer-empty:p-2 peer-empty:p-3', 'peer-empty:p-3'],
    ['group-empty:p-2 peer-empty:p-3', 'group-empty:p-2 peer-empty:p-3'],
    ['hover:group-empty:p-2 hover:group-empty:p-3', 'hover:group-empty:p-3'],
    ['group-read-only:p-2 group-read-only:p-3', 'group-read-only:p-3'],
]);
