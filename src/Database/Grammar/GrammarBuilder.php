<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar;

use InvalidArgumentException;
use Viniciuscoutinh0\Minimal\Concerns\When;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\BuilderInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\OrderByInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\SelectInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\TableInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\WhereInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\BooleanEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OrderByDirectionEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\WhereClause;

final class GrammarBuilder implements BuilderInterface, OrderByInterface, SelectInterface, TableInterface, WhereInterface
{
    use When;

    /**
     * Table name
     *
     * @var string
     */
    private string $table;

    /**
     * With distinct keyword
     *
     * @var bool
     */
    private bool $withDistinct = false;

    /**
     * Selected columns
     *
     * @var string[]
     */
    private array $columns = [];

    /**
     * Where clauses
     *
     * @var WhereClause[]
     */
    private array $wheres = [];

    /**
     * Order by column
     *
     * @var ?string
     */
    private ?string $orderBy = null;

    /**
     * Order by direction
     *
     * @var ?OrderByDirectionEnum
     */
    private ?OrderByDirectionEnum $orderByDirection = null;

    /**
     * Set the table name
     *
     * @param  string  $table
     * @return SelectInterface|SelectInterface
     */
    public function table(string $table): SelectInterface|WhereInterface
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Select columns.
     *
     * @param  string[]  ...$columns
     * @return WhereInterface
     */
    public function select(...$columns): WhereInterface
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Set the distinct keyword.
     *
     * @param  bool  $withDistinct
     * @return SelectInterface
     */
    public function distinct(bool $withDistinct = true): SelectInterface
    {
        $this->withDistinct = $withDistinct;

        return $this;
    }

    /**
     * Add a where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return WhereInterface
     */
    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): WhereInterface
    {
        $this->wheres[] = new WhereClause(
            column: $column,
            operator: $operator,
            value: $value,
            boolean: BooleanEnum::And
        );

        return $this;
    }

    /**
     * Add a or where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     */
    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): WhereInterface
    {
        $this->wheres[] = new WhereClause(
            column: $column,
            operator: $operator,
            value: $value,
            boolean: BooleanEnum::Or
        );

        return $this;
    }

    public function whereIn(string $column, array $values): WhereInterface
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Values cannot be empty');
        }

        $this->wheres[] = new WhereClause(
            column: $column,
            operator: OperatorEnum::In,
            value: $values,
            boolean: BooleanEnum::And
        );

        return $this;
    }

    public function whereNotIn(string $column, array $values): WhereInterface
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Values cannot be empty');
        }

        $this->wheres[] = new WhereClause(
            column: $column,
            operator: OperatorEnum::NotIn,
            value: $values,
            boolean: BooleanEnum::And
        );

        return $this;
    }

    /**
     * Add a or where in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereIn(string $column, array $values): WhereInterface
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Values cannot be empty');
        }

        $this->wheres[] = new WhereClause(
            column: $column,
            operator: OperatorEnum::In,
            value: $values,
            boolean: BooleanEnum::Or
        );

        return $this;
    }

    /**
     * Add a or where not in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereNotIn(string $column, array $values): WhereInterface
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Values cannot be empty');
        }

        $this->wheres[] = new WhereClause(
            column: $column,
            operator: OperatorEnum::NotIn,
            value: $values,
            boolean: BooleanEnum::Or
        );

        return $this;
    }

    /**
     * Order by column
     *
     * @param  string  $column
     * @param  OrderByDirectionEnum|null  $direction
     * @return static
     */
    public function orderBy(string $column, ?OrderByDirectionEnum $direction = null): static
    {
        $this->orderBy = $column;

        $this->orderByDirection = $direction;

        return $this;
    }

    /**
     * Order by column desc
     *
     * @param  string  $column
     * @return static
     */
    public function orderByDesc(string $column): static
    {
        $this->orderBy($column, OrderByDirectionEnum::Desc);

        return $this;
    }

    /**
     * Check if has order by
     *
     * @return bool
     */
    public function hasOrderBy(): bool
    {
        return $this->orderBy !== null;
    }

    /**
     * Get order by column
     *
     * @return ?string
     */
    public function getOrderByColumn(): ?string
    {
        return $this->orderBy;
    }

    /**
     * Get order by direction
     *
     * @return ?OrderByDirectionEnum
     */
    public function getOrderByDirection(): ?OrderByDirectionEnum
    {
        return $this->orderByDirection;
    }

    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function toSql(): string
    {
        $sql = 'select';

        $this->addDistinctKeyword($sql);

        $sql .= " {$this->normalizeColumns()}";
        $sql .= " from {$this->table}";

        $this->addWhereClause($sql);

        $this->addOrderBy($sql);

        return $sql;
    }

    /**
     * Get the bindings for the query.
     *
     * @return array
     */
    public function bindings(): array
    {
        return array_map(fn (WhereClause $whereClause): mixed => $whereClause->value, $this->wheres);
    }

    /**
     * Normalize the columns.
     *
     * @return string
     */
    private function normalizeColumns(): string
    {
        if (! count($this->columns)) {
            return '*';
        }

        return implode(', ', $this->columns);
    }

    /**
     * Add the distinct keyword to the query.
     *
     * @param  string  $queryString
     * @return void
     */
    private function addDistinctKeyword(string &$queryString): void
    {
        if (! $this->withDistinct) {
            return;
        }

        $queryString .= ' distinct';
    }

    /**
     * Add the where clause to the query.
     *
     * @param  string  $sql
     * @return void
     */
    private function addWhereClause(string &$sql): void
    {
        if (empty($this->wheres)) {
            return;
        }

        $conditions = [];

        /** @var WhereClause $whereClause */
        foreach ($this->wheres as $index => $whereClause) {
            $placeholder = match ($whereClause->operator) {
                OperatorEnum::In, OperatorEnum::NotIn => '('.implode(', ', array_fill(0, count($whereClause->value), '?')).')',
                default => '?'
            };

            $prefix = $index === 0 ? '' : " {$whereClause->boolean->value}";

            $conditions[] = "{$prefix} {$whereClause->column} {$whereClause->operator->value} {$placeholder}";
        }

        $sql .= ' where'.implode('', $conditions);
    }

    /**
     * Add the order by clause to the query.
     *
     * @param  string  $sql
     * @return void
     */
    private function addOrderBy(string &$sql): void
    {
        if (! $this->hasOrderBy()) {
            return;
        }

        $direction = $this->getOrderByDirection() ?? OrderByDirectionEnum::Asc;

        $sql .= " order by {$this->getOrderByColumn()} {$direction->value}";
    }
}
