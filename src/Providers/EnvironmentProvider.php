<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Dotenv\Dotenv;

final class EnvironmentProvider extends ServiceProvider
{
    /**
     * Register the environment provider.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot the environment provider.
     *
     * @return void
     */
    public function boot(): void
    {
        Dotenv::createImmutable($this->app->basePath())->load();
    }
}
