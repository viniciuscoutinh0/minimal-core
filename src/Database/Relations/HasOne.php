<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Database\Model;

final class HasOne extends Relation
{
    /**
     * Get the results of the relation
     *
     * @return Model|null
     */
    public function results(): ?Model
    {
        $query = $this->queryBuilder()->where($this->foreignKey, $this->parent->{$this->localKey});

        return $query->first();
    }
}
