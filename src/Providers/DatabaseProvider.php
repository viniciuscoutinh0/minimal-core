<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use PDO;
use Viniciuscoutinh0\Minimal\Database\Connection;
use Viniciuscoutinh0\Minimal\Database\Drivers\MSSQLDriver;

final class DatabaseProvider extends ServiceProvider
{
    /**
     * Register the database provider.
     *
     * @return void
     */
    public function register(): void
    {
        Connection::create(new MSSQLDriver(
            host: env('DB_HOST', 'localhost'),
            port: (int) env('DB_PORT', 1433),
            database: env('DB_DATABASE', 'minimal_framework'),
            username: env('DB_USERNAME', 'sa'),
            password: env('DB_PASSWORD', '123456'),
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        ));
    }

    /**
     * Boot the database provider.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
