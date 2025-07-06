<?php

namespace Lumen\TwMerge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lumen\TwMerge\TwMerge
 */
class TwMerge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lumen\TwMerge\TwMerge::class;
    }
}
