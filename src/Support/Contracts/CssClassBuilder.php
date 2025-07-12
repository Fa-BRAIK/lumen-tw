<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

use Stringable;

/**
 * @phpstan-type ClassNameValue array<string>|string|null
 */
interface CssClassBuilder extends Stringable
{
    /**
     * @param  ClassNameValue  ...$classes
     */
    public function __construct(array|string|null ...$classes);

    /**
     * @param  ClassNameValue  ...$classes
     */
    public static function staticBuild(array|string|null ...$classes): string;

    /**
     * @param  ClassNameValue  ...$classes
     */
    public function add(array|string|null ...$classes): self;

    public function build(): string;
}
