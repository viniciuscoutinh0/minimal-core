<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Database\Model;
use Viniciuscoutinh0\Minimal\Database\QueryBuilder;

/**
 * @property Model $parent
 * @property string $related
 * @property string $foreignKey
 * @property string $localKey
 */
abstract class Relation
{
    /**
     * The results of the relation
     *
     * @var Model|Collection|null
     */
    protected Model|Collection|null $cache = null;

    public function __construct(
        protected Model $parent,
        protected string $related,
        protected string $foreignKey,
        protected string $localKey
    ) {
    }

    /**
     * Get the results of the relation
     *
     * @return Model|Collection<Model>|null
     */
    abstract public function results(): Model|Collection|null;

    /**
     * Get the name of the related model
     *
     * @return string
     */
    final public function related(): string
    {
        return $this->related;
    }

    /**
     * Get the name of the foreign key
     *
     * @return string
     */
    final public function foreignKey(): string
    {
        return $this->foreignKey;
    }

    /**
     * Get the name of the local key
     *
     * @return string
     */
    final public function localKey(): string
    {
        return $this->localKey;
    }

    /**
     * Check if the relation is an instance of the given class
     *
     * @return bool
     */
    final public function is(string $class): bool
    {
        return get_class($this) === $class;
    }

    /**
     * Check if the relation is multiple
     *
     * @return bool
     */
    final public function isMultiple(): bool
    {
        return $this->is(class: HasMany::class);
    }

    /**
     * Query builder
     *
     * @return QueryBuilder
     */
    protected function builder(): QueryBuilder
    {
        return new QueryBuilder(
            model: new $this->related,
            baseClass: $this->related,
            pdo: $this->parent->connection()->pdo(),
        );
    }

    /**
     * Base where clause
     *
     * @return QueryBuilder
     */
    protected function where(): QueryBuilder
    {
        return $this->builder()->where(
            $this->foreignKey,
            $this->parent->{$this->localKey},
        );
    }
}
