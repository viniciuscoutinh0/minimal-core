<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use PDOStatement;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

final class QueryBuilder
{
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
     * Get the first record of the query.
     *
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function first(...$columns): ?Model
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        $statement = $this->prepareStatement();

        return $statement->fetchObject($this->baseClass) ?? null;
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
     * @return Model[]
     */
    public function get(...$columns): array
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        return $this->prepareStatement()->fetchAll(PDO::FETCH_CLASS, $this->baseClass);
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

        $statement->execute($this->grammar->bindings());

        return $statement;
    }
}
