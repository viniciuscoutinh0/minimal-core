<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar;

use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\BuilderInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\SelectInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\TableInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Contracts\WhereInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\BooleanEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\WhereClause;

final class GrammarBuilder implements BuilderInterface, SelectInterface, TableInterface, WhereInterface
{
    private string $table;

    private bool $withDistinct = false;

    /** @var string[] */
    private array $columns = [];

    /** @var WhereClause[] */
    private array $wheres = [];

    public function table(string $table): SelectInterface|WhereInterface
    {
        $this->table = $table;

        return $this;
    }

    public function select(...$columns): WhereInterface
    {
        $this->columns = count($columns) ? $columns : ['*'];

        return $this;
    }

    public function distinct(bool $withDistinct = true): SelectInterface
    {
        $this->withDistinct = $withDistinct;

        return $this;
    }

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

    public function toSql(): string
    {
        $sql = 'select';

        $this->addDistinctKeyword($sql);

        $sql .= " {$this->normalizeColumns()}";
        $sql .= " from {$this->table}";

        $this->addWhereClause($sql);

        return $sql;
    }

    public function bindings(): array
    {
        return array_map(fn (WhereClause $whereClause): mixed => $whereClause->value, $this->wheres);
    }

    private function normalizeColumns(): string
    {
        return implode(', ', $this->columns);
    }

    private function addDistinctKeyword(string &$queryString): void
    {
        if (! $this->withDistinct) {
            return;
        }

        $queryString .= ' distinct';
    }

    private function addWhereClause(string &$sql): void
    {
        if (empty($this->wheres)) {
            return;
        }

        $conditions = [];

        /** @var WhereClause $whereClause */
        foreach ($this->wheres as $index => $whereClause) {
            $prefix = $index === 0 ? '' : " {$whereClause->boolean->value}";

            $conditions[] = "{$prefix} {$whereClause->column} {$whereClause->operator->value} ?";
        }

        $sql .= ' where'.implode('', $conditions);
    }
}
