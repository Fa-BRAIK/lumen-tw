<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\Contracts\Config;

it('can get the default config', function (): void {
    $defaultConfig = app('twMerge')->getDefaultConfig();

    expect($defaultConfig)
        ->toBeInstanceOf(Config::class)
        ->toHaveProperty('cacheSize', config('lumen-tw.cache_size'))
        ->toHaveProperty('prefix', config('lumen-tw.prefix'))
        ->not()
        ->toHaveProperty('nonExistent')
        ->and($defaultConfig->classGroups)
        ->toHaveKey('display.0', 'block')
        ->toHaveKey('overflow.0.overflow.0', 'auto')
        ->not()
        ->toHaveKey('overflow.0.nonExistent');
});
