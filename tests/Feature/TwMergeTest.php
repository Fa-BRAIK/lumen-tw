<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
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
    expect(tw_merge(...$inputs))->toBe($expected);
})->with($data);

it('can perform class merge using a facade', function (array $inputs, string $expected): void {
    expect(TwMerge::merge(...$inputs))->toBe($expected);
})->with($data);

it('can perform class merge using a blade directive', function (): void {
    expect(Blade::compileString('@twMerge("foo bar baz")'))
        ->toBe('<?php echo tw_merge("foo bar baz"); ?>');
});

it('can perform class merge using component attribute bag macro', function (): void {
    $attributes = new ComponentAttributeBag(['class' => 'text-base text-center']);

    /** @var ComponentAttributeBag $attributes */
    $attributes = $attributes->twMerge('text-lg')->twMerge('text-primary');

    expect($attributes->get('class'))->toBe('text-center text-lg text-primary');
});
