<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;

interface WhereInterface extends BuilderInterface
{
    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self;

    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self;
}
