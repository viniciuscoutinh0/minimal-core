<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Database\Model;

final class HasMany extends Relation
{
    /**
     * Get the results of the relation
     *
     * @return Collection<Model>
     */
    public function results(): Collection
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = $this->where()->get();
    }
}
