<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Database\Model;

final class HasOne extends Relation
{
    /**
     * Get the result of the relation
     *
     * @return Model|null
     */
    public function results(): Model|null
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = $this->where()->first();
    }
}
