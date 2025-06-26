<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

abstract class Model
{
    protected string $table;

    protected string $primaryKey = 'id';

    final public function connection(): Connection
    {
        return Connection::instance();
    }

    final public function query(): QueryBuilder
    {
        return new QueryBuilder(
            model: $this,
            pdo: $this->connection()->pdo(),
        );
    }

    final public function table(): string
    {
        return $this->table;
    }

    final public function primaryKey(): string
    {
        return $this->primaryKey;
    }
}
