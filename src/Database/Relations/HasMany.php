<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Collection;

final class HasMany extends Relation
{
    /**
     * Get the results of the relation
     *
     * @return Collection<Model>
     */
    public function results(): Collection
    {
        $query = $this->queryBuilder()->where($this->foreignKey, $this->parent->{$this->localKey});

        return $query->get();
    }
}
