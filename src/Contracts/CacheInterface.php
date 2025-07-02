<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Contracts;

use Closure;

interface CacheInterface
{
    /**
     * Get an item from the cache.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Put an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $ttl
     * @return mixed
     */
    public function put(string $key, mixed $value, int $ttl): mixed;

    /**
     * Remember an item in the cache.
     *
     * @param  string  $key
     * @param  Closure  $callback
     * @param  int  $ttl
     */
    public function remember(string $key, Closure $callback, int $ttl): mixed;

    /**
     * Remember forever an item in the cache.
     *
     * @param  string  $key
     * @param  Closure  $callback
     * @return mixed
     */
    public function rememberForever(string $key, Closure $callback): mixed;

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget(string $key): bool;

    /**
     * Flush the cache.
     *
     * @return void
     */
    public function flush(): void;
}
