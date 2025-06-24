<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use RuntimeException;
use Viniciuscoutinh0\Minimal\Database\Contracts\DriverInterface;

final class Connection
{
    private static ?Connection $instance = null;

    private PDO $pdo;

    private function __construct(private DriverInterface $driver)
    {
        $this->pdo = new PDO(
            dsn: $driver->dsn(),
            username: $driver->username(),
            password: $driver->password(),
            options: $driver->options(),
        );
    }

    public static function create(DriverInterface $driver): self
    {
        if (self::$instance === null) {
            self::$instance = new self($driver);
        }

        return self::$instance;
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            throw new RuntimeException('Connection is not created yet.');
        }

        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
