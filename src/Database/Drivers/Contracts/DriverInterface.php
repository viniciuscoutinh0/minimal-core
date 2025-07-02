<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers\Contracts;

interface DriverInterface
{
    /**
     * Get the database connection DSN string.
     *
     * @return string
     */
    public function dsn(): string;

    /**
     * Get the database connection username.
     *
     * @return string|null
     */
    public function username(): ?string;

    /**
     * Get the database connection password.
     *
     * @return string|null
     */
    public function password(): ?string;

    /**
     * Get the database connection database name.
     *
     * @return string|int
     */
    public function database(): string|int;

    /**
     * Get the database connection options.
     *
     * @return array
     */
    public function options(): array;
}
