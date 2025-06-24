<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Spatie\Ignition\Ignition;

final class IgnitionProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Ignition::make()
            ->applicationPath($this->app->basePath())
            ->register();
    }
}
