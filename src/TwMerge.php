<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Lumen\TwMerge\Support\ClassListMerger;
use Lumen\TwMerge\Support\Config;
use Lumen\TwMerge\Support\Contracts\Config as ConfigContract;
use Lumen\TwMerge\Support\Contracts\CssClassBuilder as CssClassBuilderContract;
use Lumen\TwMerge\Support\CssClassBuilder;

/**
 * @phpstan-import-type AnyConfig from ConfigContract
 * @phpstan-import-type ClassNameValue from CssClassBuilderContract
 * @phpstan-import-type ConfigExtension from Config\Merger
 */
class TwMerge
{
    /** @var ?AnyConfig */
    protected static ?ConfigContract $config = null;

    protected static ?Config\Utils $configUtils = null;

    protected ?CacheRepository $cache = null;

    /**
     * @return AnyConfig
     */
    public function getDefaultConfig(): ConfigContract
    {
        return Config::getDefaultConfig();
    }

    /**
     * @return AnyConfig
     */
    public function getFinalConfig(): ConfigContract
    {
        if (null === static::$config) {
            static::$config = Config::getDefaultConfig();

            // Clear old config utils if they exist
            static::$configUtils = null;
        }

        return static::$config;
    }

    /**
     * @param  ConfigExtension  $extension
     */
    public function withAdditionalConfig(array $extension): static
    {
        static::$config = Config\Merger::mergeConfig(
            $this->getFinalConfig(),
            $extension
        );

        return $this;
    }

    public function resetConfig(): static
    {
        static::$config = null;
        static::$configUtils = null;

        return $this;
    }

    /**
     * @param  array<string>|string|null  ...$classes
     */
    public function merge(array|string|null ...$classes): string
    {
        return $this->remember(
            CssClassBuilder::staticBuild(...$classes),
            fn (string $input): string => ClassListMerger::merge(
                $input,
                $this->getConfigUtils()
            )
        );
    }

    protected function getConfigUtils(): Config\Utils
    {
        if (null === static::$configUtils) {
            static::$configUtils = new Config\Utils($this->getFinalConfig());
        }

        return static::$configUtils;
    }

    /**
     * @param  Closure(string): string  $callback
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    protected function remember(string $input, Closure $callback): string
    {
        if (null === ($cache = $this->cache())) {
            return $callback($input);
        }

        /** @var string $prefix */
        $prefix = config('lumen-tw.cache_prefix', '');

        $key = "{$prefix}::{$input}";

        if ($cache->has($key)) {
            $cachedValue = $cache->get($key);

            if (is_string($cachedValue)) {
                return $cachedValue;
            }
        }

        $mergedClasses = $callback($input);

        $cache->set($key, $mergedClasses);

        return $mergedClasses;
    }

    protected function cache(): ?CacheRepository
    {
        if (null !== $this->cache) {
            return $this->cache;
        }

        /** @var string|false|null $cacheStore */
        $cacheStore = config('lumen-tw.cache_store');

        if (false === $cacheStore) {
            return null;
        }

        return $this->cache = Cache::store($cacheStore);
    }
}
