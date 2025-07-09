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
        /** @var array<string, mixed> $config */
        $config = app('twMerge')->getDefaultConfig();

        foreach (self::$additionalConfig as $key => $additionalConfig) {
            $config[$key] = self::mergePropertyRecursively($config, $key, $additionalConfig);
        }

        return $config;
    }

    /**
     * @param  array<string, mixed>  $baseConfig
     * @return array<array-key, mixed>|list<string>|bool|float|int|string|null
     */
    protected static function mergePropertyRecursively(
        array $baseConfig,
        string $mergeKey,
        mixed $mergeValue
    ): array|bool|float|int|string|null {
        if ( ! array_key_exists($mergeKey, $baseConfig)) {
            /** @var array<string, mixed> $mergeValue */
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
            return null;
        }
        if (
            is_array($mergeValue)
            && array_is_list($mergeValue)
            && is_array($baseConfig[$mergeKey])
            && array_is_list($baseConfig[$mergeKey])
        ) {
            return [...$baseConfig[$mergeKey], ...$mergeValue];
        }

        /** @var ?array<array-key, mixed> $subBaseConfig */
        $subBaseConfig = $baseConfig[$mergeKey] ?? null;

        if (is_array($mergeValue) && ! array_is_list($subBaseConfig ?? [])) {
            if (null === $subBaseConfig) {
                return $mergeValue;
            }

            foreach ($mergeValue as $key => $value) {
                /** @var array<string, mixed> $subBaseConfig */
                $subBaseConfig[$key] = self::mergePropertyRecursively($subBaseConfig, $key, $value);
            }
        }

        return $subBaseConfig;
    }
}
