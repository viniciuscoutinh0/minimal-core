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
     * @param  OrderByDirectionEnum|null  $direction
     * @return static
     */
    public function orderBy(string $column, ?OrderByDirectionEnum $direction = null): static;

    /**
     * Order by column desc
     *
     * @param  string  $column
     * @return static
     */
    public function orderByDesc(string $column): static;

    /**
     * Check if has order by
     *
     * @return bool
     */
    public function hasOrderBy(): bool;

    /**
     * Get order by column
     *
     * @return ?string
     */
    public function getOrderByColumn(): ?string;

    /**
     * Get order by direction
     *
     * @return ?OrderByDirectionEnum
     */
    public function getOrderByDirection(): ?OrderByDirectionEnum;
}
