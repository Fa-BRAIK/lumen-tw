<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> getDefaultConfig()
 * @method static array<string, mixed> getMergedConfig()
 *
 * @see \Lumen\TwMerge\TwMerge
 */
class TwMerge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lumen\TwMerge\TwMerge::class;
    }
}
