<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support;

class Config
{
    /**
     * @var array<string, mixed>
     */
    protected static array $additionalConfig = [];

    /**
     * @return array<string, mixed>
     */
    public static function getMergedConfig(): array
    {
        $config = self::getDefaultConfig();

        foreach (self::$additionalConfig as $key => $additionalConfig) {
            $config[$key] = self::mergePropertyRecursively($config, $key, $additionalConfig);
        }

        return $config;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getDefaultConfig(): array
    {
        return [
            'cacheSize' => 500,
            'prefix' => null,
        ];
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>|bool|float|int|string|null  $mergeValue
     * @return array<string, mixed>|bool|float|int|string|null
     */
    protected static function mergePropertyRecursively(
        array $baseConfig,
        string $mergeKey,
        array|bool|float|int|string|null $mergeValue
    ): array|bool|float|int|string|null {
        if ( ! array_key_exists($mergeKey, $baseConfig)) {
            return $mergeValue;
        }
        if (is_string($mergeValue)) {
            return $mergeValue;
        }
        if (is_numeric($mergeValue)) {
            return $mergeValue;
        }
        if (is_bool($mergeValue)) {
            return $mergeValue;
        }
        if (null === $mergeValue) {
            return $mergeValue;
        }
        if (
            is_array($mergeValue)
            && array_is_list($mergeValue)
            && is_array($baseConfig[$mergeKey])
            && array_is_list($baseConfig[$mergeKey])
        ) {
            return [...$baseConfig[$mergeKey], ...$mergeValue];
        }
        if (is_array($mergeValue) && ! array_is_list($mergeValue)) {
            if (null === $baseConfig[$mergeKey]) {
                return $mergeValue;
            }
            foreach ($mergeValue as $key => $value) {
                $baseConfig[$mergeKey][$key] = self::mergePropertyRecursively($baseConfig[$mergeKey], $key, $value);
            }
        }

        return $baseConfig[$mergeKey];
    }
}
