<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Closure;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Viniciuscoutinh0\Minimal\Contracts\CacheInterface;

final class Cache implements CacheInterface
{
    public function __construct(
        private AdapterInterface $adapter
    ) {
    }

    /**
     * Get an item from the cache.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->adapter->getItem($key)->get() ?? $default;
    }

    /**
     * Put an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $ttl
     * @return mixed
     */
    public function put(string $key, mixed $value, int $ttl): mixed
    {
        $item = $this->adapter->getItem($key);

        $item->set($this->evaluate($value));
        $item->expiresAfter($ttl);

        $this->adapter->save($item);

        return $item->get();
    }

    /**
     * Remember an item in the cache.
     *
     * @param  string  $key
     * @param  Closure  $callback
     * @param  int  $ttl
     */
    public function remember(string $key, Closure $callback, int $ttl): mixed
    {
        $item = $this->adapter->getItem($key);

        if (! $item->isHit()) {
            $item->set($callback());
            $item->expiresAfter($ttl);
            $this->adapter->save($item);
        }

        return $item->get();
    }

    /**
     * Remember forever an item in the cache.
     *
     * @param  string  $key
     * @param  Closure  $callback
     * @return mixed
     */
    public function rememberForever(string $key, Closure $callback): mixed
    {
        $item = $this->adapter->getItem($key);

        if (! $item->isHit()) {
            $item->set($callback());
            $this->adapter->save($item);
        }

        return $item->get();
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->adapter->deleteItem($key);
    }

    /**
     * Flush the cache.
     *
     * @return void
     */
    public function flush(): void
    {
        $this->adapter->clear();
    }

    /**
     * Evaluate the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    private function evaluate(mixed $value): mixed
    {
        if (is_callable($value) || $value instanceof Closure) {
            return $value();
        }

        return $value;
    }
}
