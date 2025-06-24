<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Contracts;

interface ServiceProviderInterface
{
    public function register(): void;

    public function boot(): void;
}
