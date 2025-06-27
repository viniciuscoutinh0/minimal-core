<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Viniciuscoutinh0\Minimal\View;

final class ViewProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        View::share('app', $this->app);

        View::configureBasePath($this->app->basePath());
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
