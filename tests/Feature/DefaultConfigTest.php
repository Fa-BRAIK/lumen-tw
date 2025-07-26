<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\Contracts\Config;

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can get the default config', function (): void {
    expect(app('twMerge')->getDefaultConfig())
        ->toBeInstanceOf(Config::class)
        ->getCacheSize()->toBe(config('lumen-tw.cache_size'))
        ->getPrefix()->toBe(config('lumen-tw.prefix'))
        ->not()->toHaveProperty('nonExistent')
        ->getClassGroups()
        ->toHaveKey('display.0', 'block')
        ->toHaveKey('overflow.0.overflow.0', 'auto')
        ->not()
        ->toHaveKey('overflow.0.nonExistent');
});
