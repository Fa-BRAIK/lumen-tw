<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * Type of param passed to the `experimentalParseClassName` function.
 *
 * This is an experimental feature and may introduce breaking changes in any minor version update.
 */
interface ExperimentalParseClassNameParam
{
    public string $className { get; }

    public function parseClassName(string $className): ParsedClassName;
}
