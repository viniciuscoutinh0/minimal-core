<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Factory;

use RuntimeException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Viniciuscoutinh0\Minimal\Cache;
use Viniciuscoutinh0\Minimal\Contracts\CacheInterface;
use Viniciuscoutinh0\Minimal\Database\Drivers\RedisDriver;
use Viniciuscoutinh0\Minimal\Database\RedisConnection;

final class CacheFactory
{
    public static function create(): CacheInterface
    {
        $driver = env('CACHE_DRIVER', 'array');

        $adapter = match ($driver) {
            'array' => new ArrayAdapter(),

            'redis' => RedisConnection::create(
                driver: new RedisDriver(
                    host: env('REDIS_HOST', '127.0.0.1'),
                    port: (int) env('REDIS_PORT', 6379),
                    database: env('REDIS_DATABASE', 0),
                    username: env('REDIS_USERNAME'),
                    password: env('REDIS_PASSWORD'),
                )
            )->adapter(),

            default => throw new RuntimeException("Cache driver [$driver] not supported."),
        };

        return new Cache($adapter);
    }
}
