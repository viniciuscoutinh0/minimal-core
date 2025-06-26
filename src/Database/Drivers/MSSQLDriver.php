<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Drivers;

use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final readonly class MSSQLDriver implements DriverInterface
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

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function dsn(): string
    {
        return "sqlsrv:Server={$this->host},{$this->port};Database={$this->database}";
    }
}
