<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Facades;

use Illuminate\Support\Facades\Facade;
use Lumen\TwMerge\Support\Contracts\Config as ConfigContract;

/**
 * @method static ConfigContract<string, string> getDefaultConfig()
 * @method static ConfigContract<string, string> getFinalConfig()
 * @method static \Lumen\TwMerge\TwMerge withAdditionalConfig(mixed[] $extensions)
 * @method static string merge(mixed ...$classList)
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
