<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

abstract class RegexValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        $patterns = $this->patterns();
        $excludes = $this->excludes();

        $patterns = is_array($patterns) ? $patterns : [$patterns];
        $excludes = is_array($excludes) ? $excludes : [$excludes];

        if (array_any($patterns, fn (string $pattern) => (bool) preg_match($pattern, $className))) {
            return array_all($excludes, fn (string $exclude) => ! preg_match($exclude, $className));
        }

        return false;
    }

    /**
     * A regex pattern or an array of regex patterns to validate class names.
     *
     * @return string|list<string>
     */
    abstract public function patterns(): string|array;

    /**
     * a regex pattern or an array of regex patterns to exclude from validation.
     *
     * @return string|list<string>
     */
    public function excludes(): string|array
    {
        return [];
    }
}
