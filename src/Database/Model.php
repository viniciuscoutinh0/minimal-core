<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;
use PDOStatement;
use Viniciuscoutinh0\Minimal\Database\Contracts\QueryInterface;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

abstract class Model implements QueryInterface
{
    protected Connection $connection;

    protected string $table;

    protected string $primaryKey = 'id';

    protected array $attributes = [];

    public function __construct()
    {
        $this->connection = Connection::instance();
    }

    public function __call($name, $arguments)
    {
        return $this->query()->{$name}(...$arguments);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    final public function query(): GrammarBuilder
    {
        return (new GrammarBuilder)->table($this->table);
    }

    final public function first(...$columns): ?static
    {
        $query = $this->query()->select(...$columns);

        $statement = $this->prepareStatement($query);

        return $statement->fetchObject(static::class) ?? null;
    }

    final public function all(): array
    {
        $query = $this->query();

        $statement = $this->prepareStatement($query);

        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    private function prepareStatement(GrammarBuilder $query): PDOStatement
    {
        $pdo = $this->connection->pdo();

        $statement = $pdo->prepare($query->toSql(), [
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
        ]);

        $statement->execute($query->bindings());

        return $statement;
    }
}
