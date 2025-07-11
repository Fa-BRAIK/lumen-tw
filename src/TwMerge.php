<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Lumen\TwMerge\Support\Config;

class TwMerge
{
    protected ?CacheRepository $cache = null;

    /**
     * @return array{
     *     cacheSize: int|null,
     *     prefix: string|null,
     *     theme: array<string, array<array-key, mixed>>,
     *     classGroups: array<string, array<array-key, mixed>>,
     *     conflictingClassGroups: array<string, array<array-key, mixed>>,
     *     conflictingClassGroupModifiers: array<string, array<array-key, mixed>>,
     *     orderSensitiveModifiers: array<string>
     * }
     */
    public function getDefaultConfig(): array
    {
        return Config::getDefaultConfig();
    }

    /**
     * @param  Closure(string): string  $callback
     */
    protected function remember(string $input, Closure $callback): string
    {
        if (null === ($cache = $this->cache())) {
            return $callback($input);
        }

        /** @var string $prefix */
        $prefix = config('lumen-tw.prefix', '');

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
