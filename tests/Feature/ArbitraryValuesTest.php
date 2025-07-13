<?php

declare(strict_types=1);

it('handles simple conflicts with arbitrary values correctly', function (): void {
    expect(app('twMerge')->merge('m-[2px] m-[10px]'))
        ->toBe('m-[10px]')
        ->and(app('twMerge')->merge('m-[2px] m-[11svmin] m-[12in] m-[13lvi] m-[14vb] m-[15vmax] m-[16mm] m-[17%] m-[18em] m-[19px] m-[10dvh]'))
        ->toBe('m-[10dvh]')
        ->and(app('twMerge')->merge('h-[10px] h-[11cqw] h-[12cqh] h-[13cqi] h-[14cqb] h-[15cqmin] h-[16cqmax]'))
        ->toBe('h-[16cqmax]')
        ->and(app('twMerge')->merge('z-20 z-[99]'))
        ->toBe('z-[99]')
        ->and(app('twMerge')->merge('my-[2px] m-[10rem]'))
        ->toBe('m-[10rem]')
        ->and(app('twMerge')->merge('cursor-pointer cursor-[grab]'))
        ->toBe('cursor-[grab]')
        ->and(app('twMerge')->merge('m-[2px] m-[calc(100%-var(--arbitrary))]'))
        ->toBe('m-[calc(100%-var(--arbitrary))]')
        ->and(app('twMerge')->merge('m-[2px] m-[length:var(--mystery-var)]'))
        ->toBe('m-[length:var(--mystery-var)]')
        ->and(app('twMerge')->merge('opacity-10 opacity-[0.025]'))
        ->toBe('opacity-[0.025]')
        ->and(app('twMerge')->merge('scale-75 scale-[1.7]'))
        ->toBe('scale-[1.7]')
        ->and(app('twMerge')->merge('brightness-90 brightness-[1.75]'))
        ->toBe('brightness-[1.75]')
        // Handling of value `0`
        ->and(app('twMerge')->merge('min-h-[0.5px] min-h-[0]'))
        ->toBe('min-h-[0]')
        ->and(app('twMerge')->merge('text-[0.5px] text-[color:0]'))
        ->toBe('text-[0.5px] text-[color:0]')
        ->and(app('twMerge')->merge('text-[0.5px] text-(--my-0)'))
        ->toBe('text-[0.5px] text-(--my-0)');
});

it('handles arbitrary length conflicts with labels and modifiers correctly', function (): void {
    expect(app('twMerge')->merge('hover:m-[2px] hover:m-[length:var(--c)]'))
        ->toBe('hover:m-[length:var(--c)]')
        ->and(app('twMerge')->merge('hover:focus:m-[2px] focus:hover:m-[length:var(--c)]'))
        ->toBe('focus:hover:m-[length:var(--c)]')
        ->and(app('twMerge')->merge('border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]'))
        ->toBe('border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]')
        ->and(app('twMerge')->merge('border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b'))
        ->toBe('border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b')
        ->and(app('twMerge')->merge('border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-some-coloooor'))
        ->toBe('border-b border-some-coloooor');
});

it('handles complex arbitrary value conflicts correctly', function (): void {
    expect(app('twMerge')->merge('grid-rows-[1fr,auto] grid-rows-2'))
        ->toBe('grid-rows-2')
        ->and(app('twMerge')->merge('grid-rows-[repeat(20,minmax(0,1fr))] grid-rows-3'))
        ->toBe('grid-rows-3');
});

it('handles ambiguous arbitrary values correctly', function (): void {
    expect(app('twMerge')->merge('mt-2 mt-[calc(theme(fontSize.4xl)/1.125)]'))
        ->toBe('mt-[calc(theme(fontSize.4xl)/1.125)]')
        ->and(app('twMerge')->merge('p-2 p-[calc(theme(fontSize.4xl)/1.125)_10px]'))
        ->toBe('p-[calc(theme(fontSize.4xl)/1.125)_10px]')
        ->and(app('twMerge')->merge('mt-2 mt-[length:theme(someScale.someValue)]'))
        ->toBe('mt-[length:theme(someScale.someValue)]')
        ->and(app('twMerge')->merge('mt-2 mt-[theme(someScale.someValue)]'))
        ->toBe('mt-[theme(someScale.someValue)]')
        ->and(app('twMerge')->merge('text-2xl text-[length:theme(someScale.someValue)]'))
        ->toBe('text-[length:theme(someScale.someValue)]')
        ->and(app('twMerge')->merge('text-2xl text-[calc(theme(fontSize.4xl)/1.125)]'))
        ->toBe('text-[calc(theme(fontSize.4xl)/1.125)]')
        ->and(app('twMerge')->merge('bg-cover bg-[percentage:30%] bg-[size:200px_100px] bg-[length:200px_100px]'))
        ->toBe('bg-[percentage:30%] bg-[length:200px_100px]')
        ->and(app('twMerge')->merge('bg-none bg-[url(.)] bg-[image:.] bg-[url:.] bg-[linear-gradient(.)] bg-linear-to-r'))
        ->toBe('bg-linear-to-r')
        ->and(app('twMerge')->merge('border-[color-mix(in_oklab,var(--background),var(--calendar-color)_30%)] border'))
        ->toBe('border-[color-mix(in_oklab,var(--background),var(--calendar-color)_30%)] border');
});

it('handles arbitrary custom properties correctly', function (): void {
    expect(app('twMerge')->merge('bg-red bg-(--other-red) bg-bottom bg-(position:-my-pos)'))
        ->toBe('bg-(--other-red) bg-(position:-my-pos)')
        ->and(app('twMerge')->merge('shadow-xs shadow-(shadow:--something) shadow-red shadow-(--some-other-shadow) shadow-(color:--some-color)'))
        ->toBe('shadow-(--some-other-shadow) shadow-(color:--some-color)');
});
