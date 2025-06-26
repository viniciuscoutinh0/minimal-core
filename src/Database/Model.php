<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

abstract class Model
{
    protected string $table;

    protected string $primaryKey = 'id';

    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);

        $this->table = \Illuminate\Support\Str::singular(class_basename(static::class));
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    final public static function newQuery(): QueryBuilder
    {
        return (new static)->query();
    }

    final public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }
    }

    final public function connection(): Connection
    {
        return Connection::instance();
    }

    final public function query(): QueryBuilder
    {
        return new QueryBuilder(
            model: $this,
            baseClass: static::class,
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
