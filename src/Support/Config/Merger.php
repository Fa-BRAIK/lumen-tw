<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Config;

use Illuminate\Support\Arr;
use Lumen\TwMerge\Support\Contracts\Config;
use Lumen\TwMerge\Support\Contracts\ConfigGroupPart;

/**
 * @template TClassGroupIds of string
 * @template TThemeGroupIds of string
 *
 * @phpstan-import-type ThemeObject from ConfigGroupPart
 * @phpstan-import-type ClassGroup from ConfigGroupPart
 *
 * @phpstan-type PartialConfigGroupPart = array{
 *     theme?: ThemeObject,
 *     classGroups?: array<TClassGroupIds, ClassGroup>,
 *     conflictingClassGroups?: array<TClassGroupIds, array<TClassGroupIds>>,
 *     conflictingClassGroupModifiers?: array<TClassGroupIds, array<TClassGroupIds>>,
 *     orderSensitiveModifiers?: array<string>,
 * }
 * @phpstan-type ConfigExtension = array{
 *     cacheSize?: int,
 *     prefix?: string,
 *     override?: PartialConfigGroupPart,
 *     extend?: PartialConfigGroupPart,
 * }
 */
readonly class Merger
{
    /**
     * @param  Config<TClassGroupIds, TThemeGroupIds>  $config
     * @param  ConfigExtension  $extension
     * @return Config<TClassGroupIds, TThemeGroupIds>
     */
    public static function mergeConfig(Config $config, array $extension): Config
    {
        if (array_key_exists('cacheSize', $extension)) {
            $config->setCacheSize($extension['cacheSize']);
        }

        if (array_key_exists('prefix', $extension)) {
            $config->setPrefix($extension['prefix']);
        }

        if (array_key_exists('override', $extension)) {
            static::overrideConfig($config, $extension['override']);
        }

        if (array_key_exists('extend', $extension)) {
            static::extendConfig($config, $extension['extend']);
        }

        return $config;
    }

    /**
     * @param  Config<TClassGroupIds, TThemeGroupIds>  $config
     * @param  PartialConfigGroupPart  $override
     */
    protected static function overrideConfig(Config &$config, array $override): void
    {
        foreach ($override as $key => $value) {
            $functionName = 'set' . ucfirst($key);
            $getterName = 'get' . ucfirst($key);

            if ( ! method_exists($config, $functionName)) {
                continue;
            }

            $configValue = $config->{$getterName}();

            // If the dotted key ends with an integer, we need to group those values
            // For example, if we end up with ['foo.bar.0' => 'value1', 'foo.bar.1' => 'value2'],
            // we want to remove those keys and instead have ['foo.bar' => ['value1', 'value2']]
            $undotted = Arr::undot($value);
            foreach ($undotted as $dotKey => $dotValue) {
                if (is_int($dotKey)) {
                    $groupedKey = array_keys($configValue);
                    $groupedKey = array_filter($groupedKey, 'is_int');

                    if ( ! empty($groupedKey)) {
                        $configValue[$dotKey] = $dotValue;
                        unset($undotted[$dotKey]);
                    }
                }
            }

            foreach ($undotted as $dotKey => $dotValue) {
                if (null === $dotValue) {
                    continue;
                }

                Arr::set($configValue, $dotKey, $dotValue);
            }

            $config->{$functionName}($configValue); // @phpstan-ignore-line
        }
    }

    /**
     * @param  Config<TClassGroupIds, TThemeGroupIds>  $config
     * @param  PartialConfigGroupPart  $extend
     */
    protected static function extendConfig(Config &$config, array $extend): void
    {
        foreach ($extend as $key => $value) {
            $functionName = 'set' . ucfirst($key);

            if ( ! method_exists($config, $functionName)) {
                continue;
            }

            $mergeFunction = match ($key) {
                'orderSensitiveModifiers' => 'array_merge',
                default => 'array_merge_recursive'
            };

            $getterFunction = 'get' . ucfirst($key);

            $config->{$functionName}(
                $mergeFunction($config->{$getterFunction}(), $value) // @phpstan-ignore-line
            );
        }
    }
}
