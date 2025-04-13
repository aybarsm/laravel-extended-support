<?php

namespace Aybarsm\Laravel\Support\Traits;

use Illuminate\Support\Arr;

trait Cacheable
{
    protected static object $cacheable;

    public function __construct(bool $enabled, string $key, ?string $store = null, ?string $tag = null, ?int $expires = null, bool $force = false)
    {
        if ($force === false && static::$cacheable->init === true) {
            return;
        }

        static::$cacheable = (object) [];

        static::$cacheable->init = true;
        static::$cacheable->enabled = $enabled && class_exists('Illuminate\Support\Facades\Cache');

        if (! static::$cacheable->enabled) {
            return;
        }

        static::$cacheable->key = $key;
        static::$cacheable->store = ! is_null($store) && ! Arr::exists(config('cache.stores'), $store) ? $store : null;
        static::$cacheable->tag = $tag && static::$cacheable->enabled && \Illuminate\Support\Facades\Cache::supportsTags() ? $tag : null;
        static::$cacheable->expires = is_int($expires) && $expires > 0 ? $expires : (is_null($expires) ? null : \Illuminate\Support\Facades\Cache::getDefaultCacheTime());
    }

    public function cacheEnabled(): bool
    {
        return static::$cacheable->enabled;
    }

    public function getCacheKey(): string
    {
        return static::$cacheable->key;
    }

    public function getCacheTag(): ?string
    {
        return static::$cacheable->tag;
    }

    public function getCacheStoreName(): ?string
    {
        return static::$cacheable->store;
    }

    protected function cacheRemember(mixed $content): mixed
    {
        return $this->getCacheStore()->remember(static::$cacheable->key, static::$cacheable->expires, $content);
    }

    protected function cachePull(mixed $default = null)
    {
        return $this->getCacheStore()->pull(static::$cacheable->key, $default);
    }

    protected function cachePut(mixed $content): bool
    {
        return $this->getCacheStore()->put(static::$cacheable->key, static::$cacheable->expires, $content);
    }

    protected function cacheAdd(mixed $content): bool
    {
        return $this->getCacheStore()->add(static::$cacheable->key, static::$cacheable->expires, $content);
    }

    protected function cacheForget(): bool
    {
        return $this->getCacheStore()->forget(static::$cacheable->key);
    }

    protected function cacheGet(mixed $default = null)
    {
        return $this->getCacheStore()->get(static::$cacheable->key, $default);
    }

    protected function getCacheStore(): \Illuminate\Cache\TaggedCache|\Illuminate\Contracts\Cache\Repository
    {
        return $this->getCacheTag() ? \Illuminate\Support\Facades\Cache::tags($this->getCacheTag()) : \Illuminate\Support\Facades\Cache::store(static::$cacheable->store);
    }
}
