<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

use DateTimeInterface;
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

    /**
     * Add a where in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereIn(string $column, array $values): self;

    /**
     * Add a where not in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereNotIn(string $column, array $values): self;

    /**
     * Add a or where in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereIn(string $column, array $values): self;

    /**
     * Add a or where not in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereNotIn(string $column, array $values): self;

    /**
     * Add a where between clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereBetween(string $column, array $values): self;

    /**
     * Add a where not between clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereNotBetween(string $column, array $values): self;

    /**
     * Add a or where not between clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereNotBetween(string $column, array $values): self;

    /**
     * Add a or where between clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereBetween(string $column, array $values): self;

    /**
     * Add a where date clause to the query.
     *
     * @param  string  $column
     * @param  DateTimeInterface|string  $date
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function whereDate(string $column, DateTimeInterface|string $date, OperatorEnum $operator = OperatorEnum::Equal): self;

    /**
     * Add a or where date clause to the query.
     *
     * @param  string  $column
     * @param  DateTimeInterface|string  $date
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function orWhereDate(string $column, DateTimeInterface|string $date, OperatorEnum $operator = OperatorEnum::Equal): self;
}
