<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers;

use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final readonly class MySqlDriver implements DriverInterface
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
     * @return string|int
     */
    public function database(): string|int
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
        return "mysql:host={$this->host};port={$this->port};dbname={$this->database}";
    }
}
