<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('merges tailwind classes with important modifier correctly', function (string $input, string $expected): void {
    expect(app('twMerge')->merge($input))->tobe($expected);
})->with([
    ['font-medium! font-bold!', 'font-bold!'],
    ['font-medium! font-bold! font-thin', 'font-bold! font-thin'],
    ['right-2! -inset-x-px!', '-inset-x-px!'],
    ['focus:inline! focus:block!', 'focus:block!'],
    ['[--my-var:20px]! [--my-var:30px]!', '[--my-var:30px]!'],

    // Tailwind CSS v3 legacy syntax
    ['font-medium! !font-bold', '!font-bold'],
    ['!font-medium !font-bold font-thin', '!font-bold font-thin'],
    ['!right-2 !-inset-x-px', '!-inset-x-px'],
    ['focus:!inline focus:!block', 'focus:!block'],
    ['![--my-var:20px] ![--my-var:30px]', '![--my-var:30px]'],
]);
