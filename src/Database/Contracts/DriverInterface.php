<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Contracts;

interface DriverInterface
{
    public function dsn(): string;

    public function username(): string;

    public function password(): string;

    public function options(): array;
}
