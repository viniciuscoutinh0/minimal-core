<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use PDOStatement;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

final class QueryBuilder
{
    private GrammarBuilder $grammar;

    public function __construct(
        private readonly Model $model,
        private readonly string $baseClass,
        private readonly PDO $pdo,
    ) {
        $this->grammar = new GrammarBuilder;

        $this->grammar->table($this->model->table());
    }

    public function select(...$columns): self
    {
        $this->grammar->select(...$columns);

        return $this;
    }

    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->where($column, $value, $operator);

        return $this;
    }

    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->orWhere($column, $value, $operator);

        return $this;
    }

    public function first(...$columns): ?Model
    {
        $this->grammar->select($columns);

        $statement = $this->prepareStatement();

        return $statement->fetchObject($this->baseClass) ?? null;
    }

    public function find(int $id, ...$columns): ?Model
    {
        $this->grammar->where($this->model->primaryKey(), $id);

        return $this->first($columns);
    }

    public function get(): array
    {
        return $this->prepareStatement()->fetchAll(PDO::FETCH_CLASS, $this->baseClass);
    }

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
