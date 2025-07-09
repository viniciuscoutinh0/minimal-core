<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Concerns;

use Viniciuscoutinh0\Minimal\Database\Relations\BelongsTo;
use Viniciuscoutinh0\Minimal\Database\Relations\HasMany;
use Viniciuscoutinh0\Minimal\Database\Relations\HasOne;

trait HasRelation
{
    /**
     * The relations of the model
     *
     * @var array
     */
    protected array $relations = [];

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
            foreignKey: $foreignKey,
            localKey: $localKey ?: $this->primaryKey()
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
            foreignKey: $foreignKey,
            localKey: $localKey ?: $this->primaryKey()
        );
    }

    /**
     * Belongs to relation
     *
     * @param string $related
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return BelongsTo
     */
    public function belongsTo(string $related, ?string $foreignKey = null, ?string $localKey = null): BelongsTo
    {
        return new BelongsTo(
            parent: $this,
            related: $related,
            foreignKey: $foreignKey,
            localKey: $localKey ?: $this->primaryKey()
        );
    }

    /**
     * Add a relation to the model
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function relation(string $name, mixed $value): void
    {
        $this->relations[$name] = $value;
    }

    /**
     * Check if the model has a relation
     *
     * @param string $name
     * @return bool
     */
    public function hasRelation(string $name): bool
    {
        return array_key_exists($name, $this->relations);
    }

    /**
     * Get the relations of the model
     *
     * @return array
     */
    public function relations(): array
    {
        return $this->relations;
    }
}
