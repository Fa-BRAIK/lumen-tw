<?php

declare(strict_types=1);

use Lumen\TwMerge\TwMerge;

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('prefix working correctly', function (): void {
    expect(app('twMerge')->withAdditionalConfig(['prefix' => 'tw']))
        ->toBeInstanceOf(TwMerge::class)
        ->merge('tw:block tw:hidden')->toBe('tw:hidden')
        ->merge('block hidden')->toBe('block hidden')
        ->merge('tw:p-3 tw:p-2')->toBe('tw:p-2')
        ->merge('p-3 p-2')->toBe('p-3 p-2')
        ->merge('tw:right-0! tw:inset-0!')->toBe('tw:inset-0!')
        ->merge('tw:hover:focus:right-0! tw:focus:hover:inset-0!')->toBe('tw:focus:hover:inset-0!');
});
