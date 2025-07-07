<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

use Closure;
use Lumen\TwMerge\Support\Contracts\ClassValidator;

abstract class Validator implements ClassValidator
{
    protected const string FRACTION_PATTERN = '/^\d+\/\d+$/';

    protected const string TSHIRT_UNIT_PATTERN = '/^(\d+(\.\d+)?)?(xs|sm|md|lg|xl)$/';

    protected const string LENGTH_UNIT_PATTERN = '/\d+(%|px|r?em|[sdl]?v([hwib]|min|max)|pt|pc|in|cm|mm|cap|ch|ex|r?lh|cq(w|h|i|b|min|max))|\b(calc|min|max|clamp)\(.+\)|^0$/';

    protected const string COLOR_FUNCTION_PATTERN = '/^(rgba?|hsla?|hwb|(ok)?(lab|lch)|color-mix)\(.+\)$/';

    protected const string SHADOW_PATTERN = '/^(inset_)?-?((\d+)?\.?(\d+)[a-z]+|0)_-?((\d+)?\.?(\d+)[a-z]+|0)/';

    protected const string IMAGE_PATTERN = '/^(url|image|image-set|cross-fade|element|(repeating-)?(linear|radial|conic)-gradient)\(.+\)$/';

    protected const string ARBITRARY_VALUE_PATTERN = '/^\[(?:(\w[\w-]*):)?(.+)\]$/i';

    protected const string ARBITRARY_VARIABLE_PATTERN = '/^\((?:(\w[\w-]*):)?(.+)\)$/i';

    /**
     * @param  Closure(string): bool|Validator  $testLabel
     * @param  Closure(string): bool|Validator  $testValue
     */
    final protected function getIsArbitraryValue(
        string $value,
        Closure|Validator $testLabel,
        Closure|Validator $testValue,
    ): bool {
        if (preg_match(self::ARBITRARY_VALUE_PATTERN, $value, $matches)) {
            if ($matches[1]) {
                return $testLabel($matches[1]);
            }

            return $testValue($matches[2]);
        }

        return false;
    }

    /**
     * @param  Closure(string): bool  $testLabel
     */
    final protected function getIsArbitraryVariable(
        string $value,
        Closure $testLabel,
        bool $shouldMatchNoLabel = false,
    ): bool {
        if (preg_match(self::ARBITRARY_VARIABLE_PATTERN, $value, $matches)) {
            if ($matches[1]) {
                return $testLabel($matches[1]);
            }

            return $shouldMatchNoLabel;
        }

        return false;
    }

    /**
     * @param  'isLabelPosition'|'isLabelImage'|'isLabelSize'|'isLabelLength'|'isLabelNumber'|'isLabelFamilyName'|'isLabelShadow'  $functionName
     * @return Closure(string): bool
     */
    final protected function isLabelClosure(string $functionName): Closure
    {
        return fn (string $label): bool => $this->{$functionName}($label);
    }

    final protected function isLabelPosition(string $label): bool
    {
        return in_array($label, ['position', 'percentage'], true);
    }

    final protected function isLabelImage(string $label): bool
    {
        return in_array($label, ['image', 'url'], true);
    }

    final protected function isLabelSize(string $label): bool
    {
        return in_array($label, ['length', 'size', 'bg-size'], true);
    }

    final protected function isLabelLength(string $label): bool
    {
        return 'length' === $label;
    }

    final protected function isLabelNumber(string $label): bool
    {
        return 'number' === $label;
    }

    final protected function isLabelFamilyName(string $label): bool
    {
        return 'family-name' === $label;
    }

    final protected function isLabelShadow(string $label): bool
    {
        return 'shadow' === $label;
    }
}
