<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

final class HasOne extends Relation
{
    /**
     * Get the results of the relation
     *
     * @return mixed
     */
    public function results(): mixed
    {
        $query = $this->queryBuilder()->where($this->foreignKey, $this->parent->{$this->localKey});

        return $query->first();
    }
}
