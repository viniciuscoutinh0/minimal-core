<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use JsonSerializable;
use ReflectionMethod;
use RuntimeException;
use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Database\Concerns\HasCastAttribute;
use Viniciuscoutinh0\Minimal\Database\Concerns\HasRelation;
use Viniciuscoutinh0\Minimal\Database\Relations\Relation;

abstract class Model implements JsonSerializable
{
    use HasCastAttribute;
    use HasRelation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * The attributes of the model.
     *
     * @var array
     */
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        if (isset($this->relations[$key])) {
            return $this->relations[$key];
        }

        if (method_exists($this, $key)) {
            $reflection = new ReflectionMethod($this, $key);
            if ($reflection->getNumberOfRequiredParameters() === 0) {
                $relation = $this->{$key}();

                if ($relation instanceof Relation) {
                    return $this->relations[$key] = $relation->results();
                }
            }
        }

        if (! array_key_exists($key, $this->attributes)) {
            return null;
        }

        if (array_key_exists($key, $this->casts)) {
            return $this->castAttribute($key);
        }

        return $this->attributes[$key];
    }

    /**
     * Set an attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        if (method_exists($this, $key) && ($value instanceof Relation) === false) {
            $this->relations[$key] = $value;

            return;
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Get the string representation of the model.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Create a new query builder for the model.
     *
     * @return QueryBuilder
     */
    final public static function newQuery(): QueryBuilder
    {
        return (new static)->builder();
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    final public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Get the database connection instance.
     *
     * @return Connection
     */
    final public function connection(): Connection
    {
        return Connection::instance();
    }

    /**
     * Get a new query builder for the model.
     *
     * @return QueryBuilder
     */
    final public function builder(): QueryBuilder
    {
        return new QueryBuilder(
            model: $this,
            baseClass: static::class,
            pdo: $this->connection()->pdo(),
        );
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    final public function table(): string
    {
        return $this->table;
    }

    /**
     * Get the primary key associated with the table.
     *
     * @return string
     */
    final public function primaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get the attributes of the model.
     *
     * @return array
     */
    final public function toArray(): array
    {
        return array_merge($this->attributes, $this->relations);
    }

    /**
     * Get the attributes of the model as a collection.
     *
     * @return Collection
     */
    final public function toCollection(): Collection
    {
        return new Collection($this->toArray());
    }

    /**
     * Convert the model to its JSON representation.
     *
     * @param  int  $flags
     * @param  int  $depth
     * @return string
     * @throws RuntimeException
     */
    final public function toJson(int $flags = 0, int $depth = 512): string
    {
        $json = json_encode($this->toArray(), $flags, $depth);

        if ($json !== false) {
            return $json;
        }

        throw new RuntimeException('Unable to convert model to JSON.');
    }

    /**
     * Convert the model to its JSON representation.
     *
     * @return mixed
     */
    final public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
