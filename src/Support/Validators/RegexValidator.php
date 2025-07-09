<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

abstract class RegexValidator extends Validator
{
    /**
     * A regex pattern or an array of regex patterns to validate class names.
     *
     * @var string|list<string>
     */
    abstract public string|array $patterns { get; }

    /**
     * a regex pattern or an array of regex patterns to exclude from validation.
     *
     * @var string|list<string>
     */
    public string|array $excludes {
        get => [];
    }

    public function __invoke(string $className): bool
    {
        $patterns = is_array($this->patterns) ? $this->patterns : [$this->patterns];
        $excludes = is_array($this->excludes) ? $this->excludes : [$this->excludes];

        if (array_any($patterns, fn ($pattern) => (bool) preg_match($pattern, $className))) {
            return array_all($excludes, fn ($exclude) => ! preg_match($exclude, $className));
        }

        return false;
    }
}
