<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\Config;
use Lumen\TwMerge\Support\Contracts\Config as ConfigContract;
use Lumen\TwMerge\TwMerge;

it('can merge config', function (): void {
    expect($mergedConfig = Config\Merger::mergeConfig(
        config: new Config(
            cacheSize: 50,
            prefix: 'tw-',
            theme: [
                'hi' => ['ho'],
                'themeToOverride' => ['to-override'],
            ],
            classGroups: [
                'fooKey' => [['fooKey' => ['one', 'two']]],
                'bla' => [['bli' => ['blub', 'blublub']]],
                'groupToOverride' => ['this', 'will', 'be', 'overridden'],
                'groupToOverride2' => ['this', 'will', 'not', 'be', 'overridden'],
            ],
            conflictingClassGroups: [
                'toOverride' => ['groupToOverride'],
            ],
            conflictingClassGroupModifiers: [
                'hello' => ['world'],
                'toOverride' => ['groupToOverride-2'],
            ],
            orderSensitiveModifiers: ['order-1'],
        ),
        extension: [
            'cacheSize' => 100,
            'prefix' => null,
            'override' => [
                'theme' => [
                    'themeToOverride' => ['overridden'],
                ],
                'classGroups' => [
                    'groupToOverride' => ['I', 'overrode', 'you'],
                    'groupToOverride2' => null,
                ],
                'conflictingClassGroups' => [
                    'toOverride' => ['groupOverridden'],
                ],
                'conflictingClassGroupModifiers' => [
                    'toOverride' => ['overridden-2'],
                ],
                'orderSensitiveModifiers' => ['order-2'],
            ],
            'extend' => [
                'classGroups' => [
                    'fooKey' => [['fooKey' => ['bar', 'baz']]],
                    'fooKey2' => [['fooKey' => ['qux', 'quux']]],
                    'otherKey' => ['nother', 'group'],
                    'groupToOverride' => ['extended'],
                ],
                'conflictingClassGroups' => [
                    'fooKey' => ['otherKey'],
                    'otherKey' => ['fooKey', 'fooKey2'],
                ],
                'conflictingClassGroupModifiers' => [
                    'hello' => ['world2'],
                ],
                'orderSensitiveModifiers' => ['order-3'],
            ],
        ]
    ))
        ->toBeInstanceOf(Config::class)
        ->toHaveProperty('cacheSize', 100)
        ->toHaveProperty('prefix', null)
        ->and($mergedConfig->theme)
        ->toEqual([
            'hi' => ['ho'],
            'themeToOverride' => ['overridden'],
        ])
        ->and($mergedConfig->classGroups)
        ->toEqual([
            'fooKey' => [
                [
                    'fooKey' => ['one', 'two'],
                ],
                [
                    'fooKey' => ['bar', 'baz'],
                ],
            ],
            'bla' => [
                [
                    'bli' => ['blub', 'blublub'],
                ],
            ],
            'fooKey2' => [
                [
                    'fooKey' => ['qux', 'quux'],
                ],
            ],
            'otherKey' => ['nother', 'group'],
            'groupToOverride' => ['I', 'overrode', 'you', 'extended'],
            'groupToOverride2' => ['this', 'will', 'not', 'be', 'overridden'],
        ])
        ->and($mergedConfig->conflictingClassGroups)
        ->toEqual([
            'toOverride' => ['groupOverridden'],
            'fooKey' => ['otherKey'],
            'otherKey' => ['fooKey', 'fooKey2'],
        ])
        ->and($mergedConfig->conflictingClassGroupModifiers)
        ->toEqual([
            'hello' => ['world', 'world2'],
            'toOverride' => ['overridden-2'],
        ])
        ->and($mergedConfig->orderSensitiveModifiers)
        ->toEqual([
            'order-2',
            'order-3',
        ]);
});

it('can merge config from alias', function (): void {
    expect(app('twMerge')->withAdditionalConfig([
        'cacheSize' => 100,
        'prefix' => 'tw',
        'override' => [
            'theme' => [
                'aspect' => ['square'],
            ],
        ],
        'extend' => [
            'theme' => [
                'aspect' => ['video'],
            ],
        ],
    ]))
        ->toBeInstanceOf(TwMerge::class)
        ->getFinalConfig()
        ->toBeInstanceOf(ConfigContract::class)
        ->and(app('twMerge')->getFinalConfig()->cacheSize)
        ->toBe(100)
        ->and(app('twMerge')->getFinalConfig()->prefix, 'tw')
        ->and(app('twMerge')->getFinalConfig()->theme)
        ->toMatchArray([
            'aspect' => ['square', 'video'],
        ]);
});
