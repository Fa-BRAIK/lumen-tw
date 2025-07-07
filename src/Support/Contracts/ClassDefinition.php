<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

/**
 * @template TThemeGroupIds of string
 */
interface ClassDefinition
{
    /**
     * @var string|ClassValidator|ThemeGetter|array<string, array<ClassDefinition<TThemeGroupIds>>>
     */
    public string|ClassValidator|ThemeGetter|array $value { get; }
}
