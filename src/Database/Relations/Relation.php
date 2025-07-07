<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

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
     * @return mixed
     */
    abstract public function results(): mixed;

    /**
     * Query builder
     *
     * @return QueryBuilder
     */
    protected function queryBuilder(): QueryBuilder
    {
        return new QueryBuilder(
            model: new $this->related,
            baseClass: $this->related,
            pdo: $this->parent->connection()->pdo(),
        );
    }
}
