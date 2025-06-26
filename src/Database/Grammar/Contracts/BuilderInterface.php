<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface BuilderInterface
{
    public function toSql(): string;

    public function bindings(): array;
}
