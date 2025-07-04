<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use PDOStatement;
use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Concerns\When;
use Viniciuscoutinh0\Minimal\Database\Exceptions\InvalidQueryException;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OrderByDirectionEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

final class QueryBuilder
{
    use When;

    /**
     * GrammarBuilder instance.
     *
     * @var GrammarBuilder
     */
    private GrammarBuilder $grammar;

    public function __construct(
        private readonly Model $model,
        private readonly string $baseClass,
        private readonly PDO $pdo,
    ) {
        $this->grammar = new GrammarBuilder;

        $this->grammar->table($this->model->table());
    }

    /**
     * Select columns.
     *
     * @param  string[]  ...$columns
     * @return self
     */
    public function select(...$columns): self
    {
        $this->grammar->select(...$columns);

        return $this;
    }

    /**
     * Add a where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->where($column, $value, $operator);

        return $this;
    }

    /**
     * Add a or where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->orWhere($column, $value, $operator);

        return $this;
    }

    /**
     * Order by column
     *
     * @param  string  $column
     * @param  OrderByDirectionEnum|null  $direction
     * @return static
     */
    public function orderBy(string $column, ?OrderByDirectionEnum $direction = null): self
    {
        $this->grammar->orderBy($column, $direction);

        return $this;
    }

    /**
     * Order by column desc
     *
     * @param  string  $column
     * @return static
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, OrderByDirectionEnum::Desc);
    }

    /**
     * Get the first record of the query.
     *
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function first(...$columns): ?Model
    {
        return $this->findOrderBy($columns, OrderByDirectionEnum::Asc);
    }

    /**
     * Get the last record of the query.
     *
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function last(...$columns): ?Model
    {
        return $this->findOrderBy($columns, OrderByDirectionEnum::Desc);
    }

    /**
     * Find a record by its primary key.
     *
     * @param  int  $id
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function find(int $id, ...$columns): ?Model
    {
        $this->grammar->where($this->model->primaryKey(), $id);

        return $this->first(...$columns);
    }

    /**
     * Get all records of the query.
     *
     * @param  string[]  ...$columns
     * @return Collection
     */
    public function get(...$columns): Collection
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        $results = $this->prepareStatement()->fetchAll(PDO::FETCH_CLASS, $this->baseClass);

        return new Collection($results);
    }

    /**
     * Get the number of records of the query.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->prepareStatement()->rowCount();
    }

    /**
     * Prepare the statement.
     *
     * @return PDOStatement
     */
    private function prepareStatement(): PDOStatement
    {
        $pdo = $this->pdo;

        $statement = $pdo->prepare($this->grammar->toSql(), [
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
        ]);

        $bindings = $this->grammar->bindings();

        if ($statement->execute($bindings) === false) {
            throw new InvalidQueryException(
                sql: $this->grammar->toSql(),
                bindings: $bindings,
                message: $pdo->errorInfo()[2] ?? 'Unknown SQL error',
            );
        }

        return $statement;
    }

    /**
     * Find and order by a record.
     *
     * @param  string[]  $columns
     * @param  ?OrderByDirectionEnum  $direction
     * @return ?Model
     */
    private function findOrderBy(array $columns = [], ?OrderByDirectionEnum $direction = null): ?Model
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        /**
         * If an ORDER BY clause is defined, use its column; otherwise, fallback to the table's primary key.
         */
        $this->grammar->orderBy(
            $this->grammar->hasOrderBy()
                ? $this->grammar->getOrderByColumn()
                : $this->model->primaryKey(),
            $direction
        );

        $statement = $this->prepareStatement();

        return $statement->fetchObject($this->baseClass) ?? null;
    }
}
