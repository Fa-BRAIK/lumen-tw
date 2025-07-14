<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

it('provides a blade directive to merge tailwind classes', function (): void {
    $this->blade('<div class="@twMerge("h-4 h-6")"></div>')
        ->assertSee('class="h-6"', false);
});

test('name ot the blade directive is configurable', function (): void {
    Config::set('lumen-tw.tw_merge_directive', 'myMerge');

    $this->blade('<div class="@myMerge("h-4 h-6")"></div>')
        ->assertSee('class="h-6"', false);
});

test('blade directive can be disabled', function (): void {
    Config::set('lumen-tw.tw_merge_directive', null);

    $this->blade('<div class="@twMerge("h-4 h-6")"></div>')
        ->assertSee('@twMerge', false);
});
