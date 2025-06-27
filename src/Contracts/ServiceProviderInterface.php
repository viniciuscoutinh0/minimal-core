<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Contracts;

interface ServiceProviderInterface
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void;
}
