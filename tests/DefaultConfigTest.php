<?php

declare(strict_types=1);

use Illuminate\Support\Arr;

it('can get the default config', function (): void {
    $defaultConfig = app('twMerge')->getDefaultConfig();

    expect($defaultConfig)->toBeArray()
        ->and(Arr::get($defaultConfig, 'cacheSize'))->toBe(config('lumen-tw.cache_size'))
        ->and(Arr::get($defaultConfig, 'prefix'))->toBe(config('lumen-tw.prefix'))
        ->and(Arr::get($defaultConfig, 'nonExistent'))->tobeNull()
        ->and(Arr::get($defaultConfig, 'classGroups.display.0'))->toBe('block')
        ->and(Arr::get($defaultConfig, 'classGroups.overflow.0.overflow.0'))->toBe('auto')
        ->and(Arr::get($defaultConfig, 'classGroups.overflow.0.nonExistent'))->toBeNull();
});
