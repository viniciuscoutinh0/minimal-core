<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Relations;

use Viniciuscoutinh0\Minimal\Database\Model;

final class BelongsTo extends Relation
{
    /**
     * Get the results of the relation
     *
     * @return Model|null
     */
    public function results(): Model|null
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = $this->builder()
            ->where($this->foreignKey, $this->parent->{$this->localKey})
            ->first();
    }
}
