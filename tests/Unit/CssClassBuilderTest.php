<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Lumen\TwMerge\Support\CssClassBuilder;

it('can be instantiated', function (): void {
    expect(new CssClassBuilder())
        ->toHaveProperty('classes')
        ->classes
        ->tobeInstanceOf(Collection::class)
        ->tohaveCount(0)
        ->and(new CssClassBuilder('foo', ['bar']))
        ->toHaveProperty('classes')
        ->classes
        ->tobeInstanceOf(Collection::class)
        ->tohaveCount(2)
        ->build()
        ->toBe('foo bar');
});

it('can add additional classes when working with an instance', function (): void {
    expect(new CssClassBuilder('foo', ['bar']))
        ->classes
        ->toHaveCount(2)
        ->add('baz', ['qux'])
        ->classes
        ->toHaveCount(4)
        ->build()
        ->toBe('foo bar baz qux');
});

test('strings', function (string $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild($classes))->toBe($expected);
})->with([
    ['', ''],
    ['foo', 'foo'],
]);

test('strings (variadic', function (array $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild(...$classes))->toBe($expected);
})->with([
    [[''], ''],
    [['foo', 'bar'], 'foo bar'],
]);

test('arrays', function (array $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild($classes))->toBe($expected);
})->with([
    [[], ''],
    [['foo'], 'foo'],
    [['foo', 'bar'], 'foo bar'],
    [['foo', null, 'bar'], 'foo bar'],
]);

test('arrays (variadic)', function (array $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild(...$classes))->toBe($expected);
})->with([
    [[], ''],
    [[['foo'], ['bar']], 'foo bar'],
    [[['foo', 'bar'], [null, 'baz']], 'foo bar baz'],
    [[['foo', null, 'bar'], ['baz']], 'foo bar baz'],
]);

test('arrays (nested)', function (array $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild(...$classes))->toBe($expected);
})->with([
    [[[]], ''],
    [[['foo']], 'foo'],
    [[[null, ['foo', 'bar']]], 'foo bar'],
]);

test('mixed (variadic)', function (mixed $classes, string $expected): void {
    expect(CssClassBuilder::staticBuild(...$classes))->toBe($expected);
})->with([
    [[null, 'foo', [['bar']]], 'foo bar'],
    [[null, [['foo', 'bar']], 'baz', '', [[['qux']]], null], 'foo bar baz qux'],
]);
