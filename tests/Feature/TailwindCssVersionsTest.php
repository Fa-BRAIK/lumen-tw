<?php

declare(strict_types=1);

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('supports Tailwind CSS v3.3 features', function (): void {
    expect(tw_merge('text-red text-lg/7 text-lg/8'))
        ->toBe('text-red text-lg/8')
        ->and(tw_merge('start-0 start-1', 'end-0 end-1', 'ps-0 ps-1 pe-0 pe-1', 'ms-0 ms-1 me-0 me-1', 'rounded-s-sm rounded-s-md rounded-e-sm rounded-e-md', 'rounded-ss-sm rounded-ss-md rounded-ee-sm rounded-ee-md'))
        ->toBe('start-1 end-1 ps-1 pe-1 ms-1 me-1 rounded-s-md rounded-e-md rounded-ss-md rounded-ee-md')
        ->and(tw_merge('start-0 end-0 inset-0 ps-0 pe-0 p-0 ms-0 me-0 m-0 rounded-ss rounded-es rounded-s'))
        ->toBe('inset-0 p-0 m-0 rounded-s')
        ->and(tw_merge('hyphens-auto hyphens-manual'))
        ->toBe('hyphens-manual')
        ->and(tw_merge('from-0% from-10% from-[12.5%] via-0% via-10% via-[12.5%] to-0% to-10% to-[12.5%]'))
        ->toBe('from-[12.5%] via-[12.5%] to-[12.5%]')
        ->and(tw_merge('from-0% from-red'))
        ->toBe('from-0% from-red')
        ->and(tw_merge('list-image-none list-image-[url(./my-image.png)] list-image-[var(--value)]'))
        ->toBe('list-image-[var(--value)]')
        ->and(tw_merge('caption-top caption-bottom'))
        ->toBe('caption-bottom')
        ->and(tw_merge('line-clamp-2 line-clamp-none line-clamp-[10]'))
        ->toBe('line-clamp-[10]')
        ->and(tw_merge('delay-150 delay-0 duration-150 duration-0'))
        ->toBe('delay-0 duration-0')
        ->and(tw_merge('justify-normal justify-center justify-stretch'))
        ->toBe('justify-stretch')
        ->and(tw_merge('content-normal content-center content-stretch'))
        ->toBe('content-stretch')
        ->and(tw_merge('whitespace-nowrap whitespace-break-spaces'))
        ->toBe('whitespace-break-spaces');
});

it('supports Tailwind CSS v3.4 features', function (): void {
    expect(tw_merge('h-svh h-dvh w-svw w-dvw'))
        ->toBe('h-dvh w-dvw')
        ->and(tw_merge('has-[[data-potato]]:p-1 has-[[data-potato]]:p-2 group-has-[:checked]:grid group-has-[:checked]:flex'))
        ->toBe('has-[[data-potato]]:p-2 group-has-[:checked]:flex')
        ->and(tw_merge('text-wrap text-pretty'))
        ->toBe('text-pretty')
        ->and(tw_merge('w-5 h-3 size-10 w-12'))
        ->toBe('size-10 w-12')
        ->and(tw_merge('grid-cols-2 grid-cols-subgrid grid-rows-5 grid-rows-subgrid'))
        ->toBe('grid-cols-subgrid grid-rows-subgrid')
        ->and(tw_merge('min-w-0 min-w-50 min-w-px max-w-0 max-w-50 max-w-px'))
        ->toBe('min-w-px max-w-px')
        ->and(tw_merge('forced-color-adjust-none forced-color-adjust-auto'))
        ->toBe('forced-color-adjust-auto')
        ->and(tw_merge('appearance-none appearance-auto'))
        ->toBe('appearance-auto')
        ->and(tw_merge('float-start float-end clear-start clear-end'))
        ->toBe('float-end clear-end')
        ->and(tw_merge('*:p-10 *:p-20 hover:*:p-10 hover:*:p-20'))
        ->toBe('*:p-20 hover:*:p-20');
});

it('supports Tailwind CSS v4.0 features', function (): void {
    expect(tw_merge('transform-3d transform-flat'))
        ->toBe('transform-flat')
        ->and(tw_merge('rotate-12 rotate-x-2 rotate-none rotate-y-3'))
        ->toBe('rotate-x-2 rotate-none rotate-y-3')
        ->and(tw_merge('perspective-dramatic perspective-none perspective-midrange'))
        ->toBe('perspective-midrange')
        ->and(tw_merge('perspective-origin-center perspective-origin-top-left'))
        ->toBe('perspective-origin-top-left')
        ->and(tw_merge('bg-linear-to-r bg-linear-45'))
        ->toBe('bg-linear-45')
        ->and(tw_merge('bg-linear-to-r bg-radial-[something] bg-conic-10'))
        ->toBe('bg-conic-10')
        ->and(tw_merge('ring-4 ring-orange inset-ring inset-ring-3 inset-ring-blue'))
        ->toBe('ring-4 ring-orange inset-ring-3 inset-ring-blue')
        ->and(tw_merge('field-sizing-content field-sizing-fixed'))
        ->toBe('field-sizing-fixed')
        ->and(tw_merge('scheme-normal scheme-dark'))
        ->toBe('scheme-dark')
        ->and(tw_merge('font-stretch-expanded font-stretch-[66.66%] font-stretch-50%'))
        ->toBe('font-stretch-50%')
        ->and(tw_merge('col-span-full col-2 row-span-3 row-4'))
        ->toBe('col-2 row-4')
        ->and(tw_merge('via-red-500 via-(--mobile-header-gradient)'))
        ->toBe('via-(--mobile-header-gradient)')
        ->and(tw_merge('via-red-500 via-(length:--mobile-header-gradient)'))
        ->toBe('via-red-500 via-(length:--mobile-header-gradient)');
});

it('supports Tailwind CSS v4.1 features', function (): void {
    expect(tw_merge('items-baseline items-baseline-last'))
        ->toBe('items-baseline-last')
        ->and(tw_merge('self-baseline self-baseline-last'))
        ->toBe('self-baseline-last')
        ->and(tw_merge('place-content-center place-content-end-safe place-content-center-safe'))
        ->toBe('place-content-center-safe')
        ->and(tw_merge('items-center-safe items-baseline items-end-safe'))
        ->toBe('items-end-safe')
        ->and(tw_merge('wrap-break-word wrap-normal wrap-anywhere'))
        ->toBe('wrap-anywhere')
        ->and(tw_merge('text-shadow-none text-shadow-2xl'))
        ->toBe('text-shadow-2xl')
        ->and(tw_merge('text-shadow-none text-shadow-md text-shadow-red text-shadow-red-500 shadow-red shadow-3xs'))
        ->toBe('text-shadow-md text-shadow-red-500 shadow-red shadow-3xs')
        ->and(tw_merge('mask-add mask-subtract'))
        ->toBe('mask-subtract')
        ->and(tw_merge('mask-(--foo) mask-[foo] mask-none', 'mask-linear-1 mask-linear-2', 'mask-linear-from-[position:test] mask-linear-from-3', 'mask-linear-to-[position:test] mask-linear-to-3', 'mask-linear-from-color-red mask-linear-from-color-3', 'mask-linear-to-color-red mask-linear-to-color-3', 'mask-t-from-[position:test] mask-t-from-3', 'mask-t-to-[position:test] mask-t-to-3', 'mask-t-from-color-red mask-t-from-color-3', 'mask-radial-(--test) mask-radial-[test]', 'mask-radial-from-[position:test] mask-radial-from-3', 'mask-radial-to-[position:test] mask-radial-to-3', 'mask-radial-from-color-red mask-radial-from-color-3'))
        ->toBe('mask-none mask-linear-2 mask-linear-from-3 mask-linear-to-3 mask-linear-from-color-3 mask-linear-to-color-3 mask-t-from-3 mask-t-to-3 mask-t-from-color-3 mask-radial-[test] mask-radial-from-3 mask-radial-to-3 mask-radial-from-color-3')
        ->and(tw_merge('mask-(--something) mask-[something]', 'mask-top-left mask-center mask-(position:--var) mask-[position:1px_1px] mask-position-(--var) mask-position-[1px_1px]'))
        ->toBe('mask-[something] mask-position-[1px_1px]')
        ->and(tw_merge('mask-(--something) mask-[something]', 'mask-auto mask-[size:foo] mask-(size:--foo) mask-size-[foo] mask-size-(--foo) mask-cover mask-contain'))
        ->toBe('mask-[something] mask-contain')
        ->and(tw_merge('mask-type-luminance mask-type-alpha'))
        ->toBe('mask-type-alpha')
        ->and(tw_merge('shadow-md shadow-lg/25 text-shadow-md text-shadow-lg/25'))
        ->toBe('shadow-lg/25 text-shadow-lg/25')
        ->and(tw_merge('drop-shadow-some-color drop-shadow-[#123456] drop-shadow-lg drop-shadow-[10px_0]'))
        ->toBe('drop-shadow-[#123456] drop-shadow-[10px_0]')
        ->and(tw_merge('drop-shadow-[#123456] drop-shadow-some-color'))
        ->toBe('drop-shadow-some-color')
        ->and(tw_merge('drop-shadow-2xl drop-shadow-[shadow:foo]'))
        ->toBe('drop-shadow-[shadow:foo]');
});

it('supports Tailwind CSS v4.1.5 features', function (): void {
    expect(tw_merge('h-12 h-lh'))
        ->toBe('h-lh')
        ->and(tw_merge('min-h-12 min-h-lh'))
        ->toBe('min-h-lh')
        ->and(tw_merge('max-h-12 max-h-lh'))
        ->toBe('max-h-lh');
});
