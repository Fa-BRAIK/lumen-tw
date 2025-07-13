<?php

declare(strict_types=1);

it('merges classes with per-side border colors correctly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['border-t-some-blue border-t-other-blue', 'border-t-other-blue'],
    ['border-t-some-blue border-some-blue', 'border-some-blue'],
    ['border-some-blue border-s-some-blue', 'border-some-blue border-s-some-blue'],
    ['border-e-some-blue border-some-blue', 'border-some-blue'],
]);
