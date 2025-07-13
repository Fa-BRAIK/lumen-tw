<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('handles color conflicts properly', function (): void {
    expect(app('twMerge')->merge('bg-grey-5 bg-hotpink'))
        ->toBe('bg-hotpink')
        ->and(app('twMerge')->merge('hover:bg-grey-5 hover:bg-hotpink'))
        ->toBe('hover:bg-hotpink')
        ->and(app('twMerge')->merge('stroke-[hsl(350_80%_0%)] stroke-[10px]'))
        ->toBe('stroke-[hsl(350_80%_0%)] stroke-[10px]');
});
