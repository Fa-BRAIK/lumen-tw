<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('merges classes from same group correctly', function (): void {
    expect(app('twMerge')->merge('overflow-x-auto overflow-x-hidden'))
        ->toBe('overflow-x-hidden')
        ->and(app('twMerge')->merge('basis-full basis-auto'))
        ->toBe('basis-auto')
        ->and(app('twMerge')->merge('w-full w-fit'))
        ->toBe('w-fit')
        ->and(app('twMerge')->merge('overflow-x-auto overflow-x-hidden overflow-x-scroll'))
        ->toBe('overflow-x-scroll')
        ->and(app('twMerge')->merge('overflow-x-auto hover:overflow-x-hidden overflow-x-scroll'))
        ->toBe('hover:overflow-x-hidden overflow-x-scroll')
        ->and(app('twMerge')->merge('overflow-x-auto hover:overflow-x-hidden hover:overflow-x-auto overflow-x-scroll'))
        ->toBe('hover:overflow-x-auto overflow-x-scroll')
        ->and(app('twMerge')->merge('col-span-1 col-span-full'))
        ->toBe('col-span-full')
        ->and(app('twMerge')->merge('gap-2 gap-px basis-px basis-3'))
        ->toBe('gap-px basis-3');
});

it('merges classes from Front Variant Numeric section correctly', function (): void {
    expect(app('twMerge')->merge('lining-nums tabular-nums diagonal-fractions'))
        ->toBe('lining-nums tabular-nums diagonal-fractions')
        ->and(app('twMerge')->merge('normal-nums tabular-nums diagonal-fractions'))
        ->toBe('tabular-nums diagonal-fractions')
        ->and(app('twMerge')->merge('tabular-nums diagonal-fractions normal-nums'))
        ->toBe('normal-nums')
        ->and(app('twMerge')->merge('tabular-nums proportional-nums'))
        ->toBe('proportional-nums');
});
