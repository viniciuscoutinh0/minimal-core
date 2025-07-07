<?php

namespace Viniciuscoutinh0\Minimal\Factory;

use RuntimeException;
use Viniciuscoutinh0\Minimal\Database\Connection;
use Viniciuscoutinh0\Minimal\Database\Drivers\MSSQLDriver;
use Viniciuscoutinh0\Minimal\Database\Drivers\MySqlDriver;
use Viniciuscoutinh0\Minimal\Database\Drivers\SqliteDriver;

final readonly class DatabaseConnectionFactory
{
    /**
     *  Create a new database connection instance.
     * 
     * @return Connection|null
     */
    public static function create(): Connection|null
    {
        $connection = env('DB_CONNECTION', 'sqlite');

        return match ($connection) {
            'sqlite' => Connection::create(new SqliteDriver(
                database: env('DB_DATABASE', ':memory:'),
            )),

            'mysql' => Connection::create(new MySqlDriver(
                host: env('DB_HOST', '127.0.0.1'),
                port: (int) env('DB_PORT', 3306),
                database: env('DB_DATABASE', 'minimal_framework'),
                username: env('DB_USERNAME', 'root'),
                password: env('DB_PASSWORD', ''),
                options: [
                    \PDO::SQLSRV_ATTR_DIRECT_QUERY => false,
                ]
            )),

            'sqlsrv' => Connection::create(new MSSQLDriver(
                host: env('DB_HOST', 'localhost'),
                port: (int) env('DB_PORT', 1433),
                database: env('DB_DATABASE', 'minimal_framework'),
                username: env('DB_USERNAME', 'sa'),
                password: env('DB_PASSWORD', '123456'),
                options: []
            )),

            default => throw new RuntimeException("Database driver [$connection] not supported."),
        };
    }
}
