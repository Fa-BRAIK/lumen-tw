<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Lumen\TwMerge\Support\Contracts\Config;

it('can get the default config', function (): void {
    $defaultConfig = app('twMerge')->getDefaultConfig();

    expect($defaultConfig)->toBeInstanceOf(Config::class)
        ->and($defaultConfig->cacheSize)->toBe(config('lumen-tw.cache_size'))
        ->and($defaultConfig->prefix)->toBe(config('lumen-tw.prefix'))
        ->and($defaultConfig->nonExisting ?? null)->tobeNull()
        ->and(Arr::get($defaultConfig->classGroups, 'display.0'))->toBe('block')
        ->and(Arr::get($defaultConfig->classGroups, 'overflow.0.overflow.0'))->toBe('auto')
        ->and(Arr::get($defaultConfig->classGroups, 'overflow.0.nonExistent'))->toBeNull();
});
