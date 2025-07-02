<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers;

use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final readonly class RedisDriver implements DriverInterface
{
    public function __construct(
        private string $host,
        private int $port,
        private string $database,
        private string $username,
        private string $password,
        private array $options = [],
    ) {
    }

    /**
     * Get the database connection username.
     *
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }

    /**
     * Get the database connection password.
     *
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * Get the database connection options.
     *
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Get the database connection database name.
     *
     * @return string
     */
    public function database(): string
    {
        return $this->database;
    }

    /**
     * Get the database connection DSN string.
     *
     * @return string
     */
    public function dsn(): string
    {
        $auth = sprintf('%s:%s@', $this->username, $this->password);

        return "redis://{$auth}{$this->host}:{$this->port}/{$this->database}";
    }
}
