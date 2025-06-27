<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;

interface WhereInterface extends BuilderInterface
{
    /**
     * Add a where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self;

    /**
     * Add a or where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self;
}
