<?php

declare(strict_types=1);

use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    app('twMerge')->resetConfig();
});

it('uses caching', function (): void {
    Event::fake();

    expect(tw_merge('h-4 h-6'))->toBe('h-6');

    Event::assertDispatched(CacheMissed::class, 1);
    Event::assertNotDispatched(CacheHit::class);

    expect(tw_merge('h-4 h-6'))->toBe('h-6');

    Event::assertDispatched(CacheMissed::class, 1);
    Event::assertDispatched(CacheHit::class, 2);
});
