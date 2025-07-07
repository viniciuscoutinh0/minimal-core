<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers;

use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final readonly class SqliteDriver implements DriverInterface
{
    public function __construct(
        private string $database,
        private array $options = [],
    ) {
    }

    /**
     * Get the database connection DSN string.
     *
     * @return string
     */
    public function dsn(): string
    {
        return "sqlite:{$this->database()}";
    }

    /**
     * Get the database connection username.
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return null;
    }

    /**
     * Get the database connection password.
     *
     * @return string|null
     */
    public function password(): ?string
    {
        return null;
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
     * Get the database connection options.
     *
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }
}
