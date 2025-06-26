<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface TableInterface
{
    public function table(string $table): SelectInterface|WhereInterface;
}
