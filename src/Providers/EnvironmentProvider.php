<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Dotenv\Dotenv;

final class EnvironmentProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Dotenv::createImmutable($this->app->basePath())->load();
    }
}
