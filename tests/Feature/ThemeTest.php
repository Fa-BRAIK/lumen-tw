<?php

declare(strict_types=1);

use Lumen\TwMerge\Support\ThemeGetter;

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can extend the theme', function (): void {
    app('twMerge')->withAdditionalConfig([
        'extend' => [
            'theme' => [
                'spacing' => ['my-space'],
                'leading' => ['my-leading'],
            ],
        ],
    ]);

    expect(app('twMerge')->merge('p-3 p-my-space p-my-margin'))->toBe('p-my-space p-my-margin')
        ->and(app('twMerge')->merge('leading-3 leading-my-leading'))->toBe('leading-my-leading');
});

it('can extend the theme object', function (): void {
    app('twMerge')->withAdditionalConfig([
        'extend' => [
            'theme' => [
                'my-theme' => ['hallo', 'hello'],
            ],
            'classGroups' => [
                'px' => [
                    [
                        'px' => [ThemeGetter::fromTheme('my-theme')],
                    ],
                ],
            ],
        ],
    ]);

    expect(app('twMerge')->merge('p-3 p-hello p-hallo'))->toBe('p-3 p-hello p-hallo')
        ->and(app('twMerge')->merge('px-3 px-hello px-hallo'))->toBe('px-hallo');
});
