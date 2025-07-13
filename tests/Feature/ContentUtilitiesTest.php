<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('can merge content utilities correctly', function (): void {
    expect(app('twMerge')->merge("content-['hello'] content-[attr(data-content)]"))
        ->toBe('content-[attr(data-content)]');
});
