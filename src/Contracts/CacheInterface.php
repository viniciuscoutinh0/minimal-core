<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Contracts;

use Closure;

interface CacheInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function put(string $key, Closure $callback, int $ttl): mixed;

    public function remember(string $key, Closure $callback, int $ttl): mixed;

    public function rememberForever(string $key, Closure $callback): mixed;

    public function forget(string $key): bool;
}
