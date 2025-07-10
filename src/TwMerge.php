<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lumen\TwMerge\Support\ClassParser;
use Lumen\TwMerge\Support\Config;
use Lumen\TwMerge\Support\ValueObjects\ParsedClass;
use Psr\SimpleCache\InvalidArgumentException;

class TwMerge
{
    /**
     * @var array<string, mixed>
     */
    protected static array $additionalConfig = [];

    /**
     * @var array<string, mixed>
     */
    protected static ?array $mergedConfig = null;

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
     * @return array<string, mixed>
     */
    public function getMergedConfig(): array
    {
        if (null !== self::$mergedConfig) {
            return self::$mergedConfig;
        }

        $config = Config::getDefaultConfig();

        foreach (self::$additionalConfig as $key => $additionalConfig) {
            $config[$key] = Config::mergePropertyRecursively(
                baseConfig: $config,
                mergeKey: $key,
                mergeValue: $additionalConfig
            );
        }

        return self::$mergedConfig = $config;
    }

    /**
     * @param  array<string, mixed>  $additionalConfig
     */
    public function withAdditionalConfig(array $additionalConfig): static
    {
        self::$additionalConfig = array_merge(self::$additionalConfig, $additionalConfig);
        self::$mergedConfig = null; // Reset merged config to force re-merge on next call

        return $this;
    }

    /**
     * @param  string|array<array-key, string|array<array-key, string>>  ...$args
     *
     * @throws InvalidArgumentException
     */
    public function merge(...$args): string
    {
        $input = collect($args)->flatten()->join(' ');

        return $this->remember($input, function (string $input): string {
            $conflictingClassGroups = [];

            $parser = new ClassParser($this->getMergedConfig()); // @phpstan-ignore-line

            return Str::of($input)
                ->trim()
                ->split('/\s+/')
                ->map(fn (string $class): ParsedClass => $parser->parse($class))
                ->reverse()
                ->map(function (ParsedClass $class) use (&$conflictingClassGroups): ?string {
                    if ($class->isExternal) {
                        return $class->originalClassName;
                    }

                    $classId = $class->modifierId . $class->classGroupId;

                    if (array_key_exists($classId, $conflictingClassGroups)) {
                        return null;
                    }

                    $conflictingClassGroups[$classId] = true;
                    $conflictingGroups = $this->getConflictingClassGroupIds(
                        $class->classGroupId,
                        $class->hasPostfixModifier
                    );

                    foreach ($conflictingGroups as $group) {
                        $conflictingClassGroups[$class->modifierId . $group] = true;
                    }

                    return $class->originalClassName;
                })
                ->reverse()
                ->filter()
                ->join(' ');
        });
    }

    /**
     * @param  Closure(string): string  $callback
     *
     * @throws InvalidArgumentException
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

    /**
     * @return array<array-key, string>
     */
    protected function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier): array
    {
        /** @var array<array-key, string> $conflicts */
        $conflicts = $this->getMergedConfig()['conflictingClassGroups'][$classGroupId] ?? []; // @phpstan-ignore-line

        if ($hasPostfixModifier && isset($this->getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId])) { // @phpstan-ignore-line
            /** @var array<array-key, string> $conflictingModifiers */
            $conflictingModifiers = $this->getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId];

            return [...$conflicts, ...$conflictingModifiers];
        }

        return $conflicts;
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
