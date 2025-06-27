<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use RuntimeException;
use Viniciuscoutinh0\Minimal\Database\Drivers\Contracts\DriverInterface;

final class Connection
{
    /**
     * Connection instance.
     *
     * @var Connection|null
     */
    private static ?Connection $instance = null;

    /**
     * Pdo instance.
     *
     * @var PDO
     */
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

    /**
     * Create a new connection instance.
     *
     * @param  DriverInterface  $driver
     * @return Connection
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
     * @return Connection
     */
    public static function instance(): self
    {
        if (self::$instance === null) {
            throw new RuntimeException('Connection is not created yet.');
        }

        return self::$instance;
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
