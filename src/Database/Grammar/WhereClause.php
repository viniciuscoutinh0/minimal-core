<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar;

use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\BooleanEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;

/**
 * @property string $column
 * @property OperatorEnum $operator
 * @property mixed $value
 * @property BooleanEnum $boolean
 */
final readonly class WhereClause
{
    public function __construct(
        public string $column,
        public OperatorEnum $operator,
        public mixed $value,
        public BooleanEnum $boolean,
    ) {
    }
}
