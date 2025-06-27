<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface TableInterface
{
    /**
     * Set the table name
     *
     * @param  string  $table
     * @return SelectInterface|SelectInterface
     */
    public function table(string $table): SelectInterface|WhereInterface;
}
