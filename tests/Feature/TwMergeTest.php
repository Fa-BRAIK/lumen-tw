<?php

declare(strict_types=1);

use Lumen\TwMerge\Facades\TwMerge;

$data = [
    [['mix-blend-normal mix-blend-multiply'], 'mix-blend-multiply'],
    [['h-10 h-min'], 'h-min'],
    [['stroke-black stroke-1'], 'stroke-black stroke-1'],
    [['stroke-2 stroke-[3]'], 'stroke-[3]'],
    [['outline-black outline-1'], 'outline-black outline-1'],
    [['grayscale-0 grayscale-[50%]'], 'grayscale-[50%]'],
    [['grow grow-[2]'], 'grow-[2]'],
    [['grow', [null, [['grow-[2]']]]], 'grow-[2]'],
];

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can perform class merge using a global function', function (array $inputs, string $expected): void {
    expect(twMerge(...$inputs))->toBe($expected);
})->with($data);

it('can perform class merge using a facade', function (array $inputs, string $expected): void {
    expect(TwMerge::merge(...$inputs))->toBe($expected);
})->with($data);
