<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

interface ThemeGetter
{
    public true $isThemeGetter { get; }

    /**
     * @template TAnyThemeGroupIds of string
     * @template TAnyClassGroupIds of string
     *
     * @param  array<TAnyThemeGroupIds, ClassDefinition<TAnyThemeGroupIds>>  $theme
     * @return ClassDefinition<TAnyClassGroupIds>
     */
    public function __invoke(array $theme): ClassDefinition;
}
