<?php

declare(strict_types=1);

it('handles arbitrary property conflicts correctly', function (): void {
    expect(app('twMerge')->merge('[paint-order:markers] [paint-order:normal]'))
        ->toBe('[paint-order:normal]')
        ->and(app('twMerge')->merge('[paint-order:markers] [--my-var:2rem] [paint-order:normal] [--my-var:4px]'))
        ->toBe('[paint-order:normal] [--my-var:4px]');
});

it('handles arbitrary property conflicts with modifiers correctly', function (): void {
    expect(app('twMerge')->merge('[paint-order:markers] hover:[paint-order:normal]'))
        ->toBe('[paint-order:markers] hover:[paint-order:normal]')
        ->and(app('twMerge')->merge('hover:[paint-order:markers] hover:[paint-order:normal]'))
        ->toBe('hover:[paint-order:normal]')
        ->and(app('twMerge')->merge('hover:focus:[paint-order:markers] focus:hover:[paint-order:normal]'))
        ->toBe('focus:hover:[paint-order:normal]')
        ->and(app('twMerge')->merge('[paint-order:markers] [paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]'))
        ->toBe('[paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]')
        ->and(app('twMerge')->merge('bg-[#B91C1C] bg-radial-[at_50%_75%] bg-radial-[at_25%_25%]'))
        ->toBe('bg-[#B91C1C] bg-radial-[at_25%_25%]');
});

it('handles complex arbitrary property conflicts correctly', function (): void {
    expect(app('twMerge')->merge('[-unknown-prop:::123:::] [-unknown-prop:url(https://hi.com)]'))
        ->toBe('[-unknown-prop:url(https://hi.com)]');
});

it('handles important modifier correctly', function (): void {
    expect(app('twMerge')->merge('![some:prop] [some:other]'))
        ->toBe('![some:prop] [some:other]')
        ->and(app('twMerge')->merge('![some:prop] [some:other] [some:one] ![some:another]'))
        ->toBe('[some:one] ![some:another]');
});
