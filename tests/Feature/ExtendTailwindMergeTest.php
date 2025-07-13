<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can extend the config with single config', function (): void {
    app('twMerge')
        ->withAdditionalConfig([
            'cacheSize' => 20,
            'extend' => [
                'classGroups' => [
                    'fooKey' => [['fooKey' => ['bar', 'baz']]],
                    'fooKey2' => [['fooKey' => ['qux', 'quux']], 'other-2'],
                    'otherKey' => ['nother', 'group'],
                ],
                'conflictingClassGroups' => [
                    'fooKey' => ['otherKey'],
                    'otherKey' => ['fooKey', 'fooKey2'],
                ],
            ],
        ]);

    expect(app('twMerge')->merge(''))
        ->toBe('')
        ->and(app('twMerge')->merge('my-modifier:fooKey-bar my-modifier:fooKey-baz'))
        ->toBe('my-modifier:fooKey-baz')
        ->and(app('twMerge')->merge('other-modifier:fooKey-bar other-modifier:fooKey-baz'))
        ->toBe('other-modifier:fooKey-baz')
        ->and(app('twMerge')->merge('group fooKey-bar'))
        ->toBe('fooKey-bar')
        ->and(app('twMerge')->merge('fooKey-bar group'))
        ->toBe('group')
        ->and(app('twMerge')->merge('group other-2'))
        ->toBe('group other-2')
        ->and(app('twMerge')->merge('other-2 group'))
        ->toBe('group')
        ->and(app('twMerge')->merge('p-10 p-20'))
        ->toBe('p-20')
        ->and(app('twMerge')->merge('hover:focus:p-10 focus:hover:p-20'))
        ->toBe('focus:hover:p-20');
});

it('can extend the config with multiple configs', function (): void {
    app('twMerge')
        ->withAdditionalConfig([
            'cacheSize' => 20,
            'extend' => [
                'classGroups' => [
                    'fooKey' => [['fooKey' => ['bar', 'baz']]],
                    'fooKey2' => [['fooKey' => ['qux', 'quux']], 'other-2'],
                    'otherKey' => ['nother', 'group'],
                ],
                'conflictingClassGroups' => [
                    'fooKey' => ['otherKey'],
                    'otherKey' => ['fooKey', 'fooKey2'],
                ],
            ],
        ])
        ->withAdditionalConfig([
            'extend' => [
                'classGroups' => [
                    'secondConfigKey' => ['hi-there', 'hello'],
                ],
            ],
        ]);

    expect(app('twMerge')->merge(''))
        ->toBe('')
        ->and(app('twMerge')->merge('my-modifier:fooKey-bar my-modifier:fooKey-baz'))
        ->toBe('my-modifier:fooKey-baz')
        ->and(app('twMerge')->merge('other-modifier:fooKey-bar other-modifier:fooKey-baz'))
        ->toBe('other-modifier:fooKey-baz')
        ->and(app('twMerge')->merge('group fooKey-bar'))
        ->toBe('fooKey-bar')
        ->and(app('twMerge')->merge('fooKey-bar group'))
        ->toBe('group')
        ->and(app('twMerge')->merge('group other-2'))
        ->toBe('group other-2')
        ->and(app('twMerge')->merge('other-2 group'))
        ->toBe('group')
        ->and(app('twMerge')->merge('p-10 p-20'))
        ->toBe('p-20')
        ->and(app('twMerge')->merge('hover:focus:p-10 focus:hover:p-20'))
        ->toBe('focus:hover:p-20');
});

it('can overrides and extends config correctly', function (): void {
    app('twMerge')
        ->withAdditionalConfig([
            'cacheSize' => 20,
            'override' => [
                'classGroups' => [
                    'shadow' => ['shadow-100', 'shadow-200'],
                    'customKey' => ['custom-100'],
                ],
                'conflictingClassGroups' => [
                    'p' => ['px'],
                ],
            ],
            'extend' => [
                'classGroups' => [
                    'shadow' => ['shadow-300'],
                    'customKey' => ['custom-200'],
                    'font-size' => ['text-foo'],
                ],
                'conflictingClassGroups' => [
                    'm' => ['h'],
                ],
            ],
        ]);

    expect(app('twMerge')->merge('shadow-lg shadow-100 shadow-200'))
        ->toBe('shadow-lg shadow-200')
        ->and(app('twMerge')->merge('custom-100 custom-200'))
        ->toBe('custom-200')
        ->and(app('twMerge')->merge('text-lg text-foo'))
        ->toBe('text-foo')
        ->and(app('twMerge')->merge('px-3 py-3 p-3'))
        ->toBe('py-3 p-3')
        ->and(app('twMerge')->merge('p-3 px-3 py-3'))
        ->toBe('p-3 px-3 py-3')
        ->and(app('twMerge')->merge('mx-2 my-2 h-2 m-2'))
        ->toBe('m-2')
        ->and(app('twMerge')->merge('m-2 mx-2 my-2 h-2'))
        ->toBe('m-2 mx-2 my-2 h-2');
});
