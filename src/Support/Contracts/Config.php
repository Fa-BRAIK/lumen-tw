<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * @template TClassGroupIds of string
 * @template TThemeGroupIds of string
 *
 * @extends ConfigGroupPart<TClassGroupIds, TThemeGroupIds>
 */
interface Config extends ConfigGroupPart, ConfigStaticPart {}
