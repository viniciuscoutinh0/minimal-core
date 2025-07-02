<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers;

use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final readonly class RedisDriver implements DriverInterface
{
    public function __construct(
        private string $host,
        private int $port,
        private string|int $database,
        private ?string $username = null,
        private ?string $password = null,
        private array $options = [],
    ) {
    }

    /**
     * Get the database connection username.
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * Get the database connection password.
     *
     * @return string|null
     */
    public function password(): ?string
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
        $dsn = 'redis://';

        $auth = '';
        if ($this->username !== null) {
            $auth .= urlencode((string) $this->username);
        }

        if ($this->password !== null) {
            $auth .= ':'.urlencode((string) $this->password);
        }

        if ($auth !== '') {
            $dsn .= $auth.'@';
        }

        $dsn .= "{$this->host}:{$this->port}/{$this->database}";

        return $dsn;
    }
}
