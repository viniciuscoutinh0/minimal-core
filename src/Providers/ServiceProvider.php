<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Providers;

use Viniciuscoutinh0\Minimal\Application;
use Viniciuscoutinh0\Minimal\Contracts\ServiceProviderInterface;

abstract class ServiceProvider implements ServiceProviderInterface
{
    public function __construct(protected Application $app)
    {
    }
}
