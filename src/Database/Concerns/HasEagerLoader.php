<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Concerns;

use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Database\Model;
use Viniciuscoutinh0\Minimal\Database\Relations\BelongsTo;
use Viniciuscoutinh0\Minimal\Database\Relations\Relation;

trait HasEagerLoader
{
    /**
     * The relationships to eager load.
     *
     * @var array
     */
    protected array $with = [];

    /**
     * Add relations to the query.
     *
     * @param  string[]  ...$relations
     * @return static
     */
    public function with(...$relations): static
    {
        $this->with = array_merge($this->with, $relations);

        return $this;
    }

    /**
     * Eager load the relationships for the models.
     *
     * @param  Collection<Model>  $models
     * @return void
     */
    private function eagerRelationships(Collection $models): void
    {
        foreach ($this->with as $relationName) {
            if ($models->isEmpty()) {
                continue;
            }

            if ($first = $models->first()) {
                $relation = $first->{$relationName}();
                $this->eagerOneToOne($models, $relationName, $relation);
            }
        }
    }

    /**
     * Eager load a one to one relationship.
     *
     * @param  Collection<Model>  $models
     * @param  string  $relationName
     * @param  Relation  $relation
     * @return void
     */
    private function eagerOneToOne(Collection $models, string $relationName, Relation $relation): void
    {
        $localKey = $relation->is(BelongsTo::class)
            ? $relation->foreignKey()
            : $relation->localKey();

        $foreignKey = $relation->is(BelongsTo::class)
            ? $relation->localKey()
            : $relation->foreignKey();

        $related = $relation->related();

        $keys = $models
            ->map(fn (Model $model) => $model->{$localKey})
            ->unique()
            ->values()
            ->toArray();

        $results = (new $related)::newQuery()->whereIn($foreignKey, $keys)->get();

        $dictionary = [];

        $isMultiple = $relation->isMultiple();

        foreach ($results as $result) {
            $key = $result->{$foreignKey};

            $isMultiple ?
                $dictionary[$key][] = $result
                : $dictionary[$key] = $result;
        }

        foreach ($models as $model) {
            $key = $model->{$localKey};

            $model->relation(
                $relationName,
                $isMultiple
                ? Collection::make($dictionary[$key] ?? [])
                : $dictionary[$key] ?? null
            );
        }
    }
}
