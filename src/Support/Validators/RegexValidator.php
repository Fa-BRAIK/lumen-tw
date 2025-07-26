<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

use Illuminate\Support\Arr;

abstract class RegexValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        $patterns = $this->patterns();
        $excludes = $this->excludes();

        $patterns = is_array($patterns) ? $patterns : [$patterns];
        $excludes = is_array($excludes) ? $excludes : [$excludes];

        $anyPatterns = (bool) Arr::where(
            $patterns,
            static fn (string $pattern) => (bool) preg_match($pattern, $className)
        );

        $allExcludes = count(Arr::where(
            $excludes,
            static fn (string $exclude) => ! preg_match($exclude, $className)
        )) === count($excludes);

        return $anyPatterns && $allExcludes;
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
