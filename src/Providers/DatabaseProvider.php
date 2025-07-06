<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Viniciuscoutinh0\Minimal\Factory\DatabaseConnectionFactory;

final class DatabaseProvider extends ServiceProvider
{
    /**
     * Register the database provider.
     *
     * @return void
     */
    public function register(): void
    {
        DatabaseConnectionFactory::create();
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
