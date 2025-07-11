<?php

namespace Lumen\TwMerge\Support\Contracts;

/**
 * @template TClassGroupIds of string
 * @template TThemeGroupIds of string
 *
 * @extends ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
 */
interface Config extends ConfigGroupPart, ConfigStaticPart
{
}
