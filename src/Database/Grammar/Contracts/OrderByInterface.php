<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OrderByDirectionEnum;

interface OrderByInterface
{
    /**
     * Order by column
     *
     * @param  string  $column
     * @param  OrderByDirectionEnum  $direction
     * @return static
     */
    public function orderBy(string $column, OrderByDirectionEnum $direction = OrderByDirectionEnum::Asc): static;

    /**
     * Order by column desc
     *
     * @param  string  $column
     * @return static
     */
    public function orderByDesc(string $column): static;
}
