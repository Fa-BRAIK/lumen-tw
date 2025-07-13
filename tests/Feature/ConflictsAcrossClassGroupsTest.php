<?php

declare(strict_types=1);

it('can merge content utilities correctly', function (): void {
    expect(app('twMerge')->merge("content-['hello'] content-[attr(data-content)]"))
        ->toBe('content-[attr(data-content)]');
});

it('handles conflicts across class groups correctly', function (): void {
    expect(app('twMerge')->merge('inset-1 inset-x-1'))
        ->toBe('inset-1 inset-x-1')
        ->and(app('twMerge')->merge('inset-x-1 inset-1'))
        ->toBe('inset-1')
        ->and(app('twMerge')->merge('inset-x-1 left-1 inset-1'))
        ->toBe('inset-1')
        ->and(app('twMerge')->merge('inset-x-1 inset-1 left-1'))
        ->toBe('inset-1 left-1')
        ->and(app('twMerge')->merge('inset-x-1 right-1 inset-1'))
        ->toBe('inset-1')
        ->and(app('twMerge')->merge('inset-x-1 right-1 inset-x-1'))
        ->toBe('inset-x-1')
        ->and(app('twMerge')->merge('inset-x-1 right-1 inset-y-1'))
        ->toBe('inset-x-1 right-1 inset-y-1')
        ->and(app('twMerge')->merge('right-1 inset-x-1 inset-y-1'))
        ->toBe('inset-x-1 inset-y-1')
        ->and(app('twMerge')->merge('inset-x-1 hover:left-1 inset-1'))
        ->toBe('hover:left-1 inset-1');
});

it('handles ring and shadow classes correctly', function (): void {
    expect(app('twMerge')->merge('ring shadow'))
        ->toBe('ring shadow')
        ->and(app('twMerge')->merge('ring-2 shadow-md'))
        ->toBe('ring-2 shadow-md')
        ->and(app('twMerge')->merge('shadow ring'))
        ->toBe('shadow ring')
        ->and(app('twMerge')->merge('shadow-md ring-2'))
        ->toBe('shadow-md ring-2');
});

it('can handle touch classes correctly', function (): void {
    expect(app('twMerge')->merge('touch-pan-x touch-pan-right'))
        ->toBe('touch-pan-right')
        ->and(app('twMerge')->merge('touch-none touch-pan-x'))
        ->toBe('touch-pan-x')
        ->and(app('twMerge')->merge('touch-pan-x touch-none'))
        ->toBe('touch-none')
        ->and(app('twMerge')->merge('touch-pan-x touch-pan-y touch-pinch-zoom'))
        ->toBe('touch-pan-x touch-pan-y touch-pinch-zoom')
        ->and(app('twMerge')->merge('touch-manipulation touch-pan-x touch-pan-y touch-pinch-zoom'))
        ->toBe('touch-pan-x touch-pan-y touch-pinch-zoom')
        ->and(app('twMerge')->merge('touch-pan-x touch-pan-y touch-pinch-zoom touch-auto'))
        ->toBe('touch-auto');
});

it('handles line-clamp classes correctly', function (): void {
    expect(app('twMerge')->merge('overflow-auto inline line-clamp-1'))
        ->toBe('line-clamp-1')
        ->and(app('twMerge')->merge('line-clamp-1 overflow-auto inline'))
        ->toBe('line-clamp-1 overflow-auto inline');
});
