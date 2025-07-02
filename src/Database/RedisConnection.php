<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use RuntimeException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final class RedisConnection
{
    /**
     * Instance
     *
     * @var RedisConnection
     */
    private static ?RedisConnection $instance = null;

    /**
     * Redis adapter
     *
     * @var RedisAdapter
     */
    private RedisAdapter $adapter;

    private function __construct(DriverInterface $driver)
    {
        $this->adapter = new RedisAdapter(
            redis: RedisAdapter::createConnection($driver->dsn(), $driver->options())
        );
    }

    /**
     * Create a new connection instance.
     *
     * @param  DriverInterface  $driver
     * @return RedisConnection
     */
    public static function create(DriverInterface $driver): self
    {
        if (self::$instance === null) {
            self::$instance = new self($driver);
        }

        return self::$instance;
    }

    /**
     * Get the connection instance.
     *
     * @return RedisConnection
     */
    public static function instance(): self
    {
        if (self::$instance === null) {
            throw new RuntimeException('Connection is not created yet.');
        }

        return self::$instance;
    }

    /**
     * Get the Redis adapter.
     *
     * @return RedisAdapter
     */
    public function adapter(): RedisAdapter
    {
        return $this->adapter;
    }
}
