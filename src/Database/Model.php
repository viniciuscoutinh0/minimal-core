<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use Illuminate\Support\Collection;
use JsonSerializable;
use RuntimeException;

abstract class Model implements JsonSerializable
{
    protected string $table;

    protected string $primaryKey = 'id';

    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __toString(): string
    {
        return $this->toJson();
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

    final public function toArray(): array
    {
        return $this->attributes;
    }

    final public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

    final public function toJson(int $flags = 0, int $depth = 512): string
    {
        $json = json_encode($this->toArray(), $flags, $depth);

        if ($json !== false) {
            return $json;
        }

        throw new RuntimeException('Unable to convert model to JSON.');
    }

    final public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
