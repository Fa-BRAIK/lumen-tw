<?php

declare(strict_types=1);

it('conflicts across prefix modifiers', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['hover:block hover:inline', 'hover:inline'],
    ['hover:block hover:focus:inline', 'hover:block hover:focus:inline'],
    ['hover:block hover:focus:inline focus:hover:inline', 'hover:block focus:hover:inline'],
    ['focus-within:inline focus-within:block', 'focus-within:block'],
]);

it('conflicts across postfix modifiers', function (): void {
    expect(app('twMerge')->merge('text-lg/7 text-lg/8'))
        ->toBe('text-lg/8')
        ->and(app('twMerge')->merge('text-lg/none leading-9'))
        ->toBe('text-lg/none leading-9')
        ->and(app('twMerge')->merge('leading-9 text-lg/none'))
        ->toBe('text-lg/none')
        ->and(app('twMerge')->merge('w-full w-1/2'))
        ->toBe('w-1/2');

    app('twMerge')
        ->resetConfig()
        ->withAdditionalConfig([
            'override' => [
                'classGroups' => [
                    'foo' => ['foo-1/2', 'foo-2/3'],
                    'bar' => ['bar-1', 'bar-2'],
                    'baz' => ['baz-1', 'baz-2'],
                ],
                'conflictingClassGroupModifiers' => [
                    'baz' => ['bar'],
                ],
            ],
        ]);

    expect(app('twMerge')->merge('foo-1/2 foo-2/3'))
        ->toBe('foo-2/3')
        ->and(app('twMerge')->merge('bar-1 bar-2'))
        ->toBe('bar-2')
        ->and(app('twMerge')->merge('bar-1 baz-1'))
        ->toBe('bar-1 baz-1')
        ->and(app('twMerge')->merge('bar-1/2 bar-2'))
        ->toBe('bar-2')
        ->and(app('twMerge')->merge('bar-2 bar-1/2'))
        ->toBe('bar-1/2')
        ->and(app('twMerge')->merge('bar-1 baz-1/2'))
        ->toBe('baz-1/2');
});

it('sorts modifiers correctly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['c:d:e:block d:c:e:inline', 'd:c:e:inline'],
    ['*:before:block *:before:inline', '*:before:inline'],
    ['*:before:block before:*:inline', '*:before:block before:*:inline'],
    ['x:y:*:z:block y:x:*:z:inline', 'y:x:*:z:inline'],
]);

it('sorts modifiers correctly according to orderSensitiveModifiers', function (): void {
    app('twMerge')
        ->resetConfig()
        ->withAdditionalConfig([
            'override' => [
                'classGroups' => [
                    'foo' => ['foo-1', 'foo-2'],
                ],
                'orderSensitiveModifiers' => ['a', 'b'],
            ],
        ]);

    expect(app('twMerge')->merge('c:d:e:foo-1 d:c:e:foo-2'))
        ->toBe('d:c:e:foo-2')
        ->and(app('twMerge')->merge('a:b:foo-1 a:b:foo-2'))
        ->toBe('a:b:foo-2')
        ->and(app('twMerge')->merge('a:b:foo-1 b:a:foo-2'))
        ->toBe('a:b:foo-1 b:a:foo-2')
        ->and(app('twMerge')->merge('x:y:a:z:foo-1 y:x:a:z:foo-2'))
        ->toBe('y:x:a:z:foo-2');
});
