<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface SelectInterface
{
    public function distinct(): self;

    public function select(...$columns): WhereInterface;
}
