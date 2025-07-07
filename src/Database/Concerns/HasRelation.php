<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Concerns;

use Viniciuscoutinh0\Minimal\Database\Relations\HasMany;
use Viniciuscoutinh0\Minimal\Database\Relations\HasOne;

trait HasRelation
{
    /**
     * Has one relation
     *
     * @param string $related
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return HasOne
     */
    public function hasOne(string $related, ?string $foreignKey = null, ?string $localKey = null): HasOne
    {
        return new HasOne(
            parent: $this,
            related: $related,
            foreignKey: $foreignKey ?: $this->getForeignKey(),
            localKey: $localKey ?: $this->getPrimaryKey()
        );
    }

    /**
     * Has many relation
     *
     * @param string $related
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return HasMany
     */
    public function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null): HasMany
    {
        return new HasMany(
            parent: $this,
            related: $related,
            foreignKey: $foreignKey ?: $this->getForeignKey(),
            localKey: $localKey ?: $this->getPrimaryKey()
        );
    }
}
