<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Viniciuscoutinh0\Minimal\View;

final class ViewProvider extends ServiceProvider
{
    public function register(): void
    {
        View::share('app', $this->app);

        View::configureBasePath($this->app->basePath());
    }

    public function boot(): void
    {
    }
}
