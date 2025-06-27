<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface SelectInterface
{
    /**
     * Set the distinct keyword.
     *
     * @return SelectInterface
     */
    public function distinct(bool $withDistinct = true): self;

    /**
     * Select columns.
     *
     * @param  string[]  ...$columns
     * @return WhereInterface
     */
    public function select(...$columns): WhereInterface;
}
