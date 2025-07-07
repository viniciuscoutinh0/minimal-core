<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use Exception;
use PDO;
use PDOStatement;
use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Concerns\When;
use Viniciuscoutinh0\Minimal\Database\Exceptions\InvalidQueryException;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OrderByDirectionEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;
use Viniciuscoutinh0\Minimal\Database\Relations\HasMany;
use Viniciuscoutinh0\Minimal\Database\Relations\HasOne;

final class QueryBuilder
{
    use When;

    /**
     * GrammarBuilder instance.
     *
     * @var GrammarBuilder
     */
    private GrammarBuilder $grammar;

    /**
     * Relations to be eager loaded.
     *
     * @var string[]
     */
    private array $with = [];

    public function __construct(
        private readonly Model $model,
        private readonly string $baseClass,
        private readonly PDO $pdo,
    ) {
        $this->grammar = new GrammarBuilder;

        $this->grammar->table($this->model->table());
    }

    /**
     * Add relations to the query.
     *
     * @param  string[]  ...$relations
     * @return self
     */
    public function with(...$relations): self
    {
        $this->with = array_merge($this->with, $relations);

        return $this;
    }

    /**
     * Select columns.
     *
     * @param  string[]  ...$columns
     * @return self
     */
    public function select(...$columns): self
    {
        $this->grammar->select(...$columns);

        return $this;
    }

    /**
     * Add a where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function where(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->where($column, $value, $operator);

        return $this;
    }

    /**
     * Add a or where clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  OperatorEnum  $operator
     * @return self
     */
    public function orWhere(string $column, mixed $value, OperatorEnum $operator = OperatorEnum::Equal): self
    {
        $this->grammar->orWhere($column, $value, $operator);

        return $this;
    }

    /**
     * Add a where in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereIn(string $column, array $values): self
    {
        $this->grammar->whereIn($column, $values);

        return $this;
    }

    /**
     * Add a where not in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function whereNotIn(string $column, array $values): self
    {
        $this->grammar->whereNotIn($column, $values);

        return $this;
    }

    /**
     * Add a or where in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereIn(string $column, array $values): self
    {
        $this->grammar->orWhereIn($column, $values);

        return $this;
    }

    /**
     * Add a or where not in clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return self
     */
    public function orWhereNotIn(string $column, array $values): self
    {
        $this->grammar->orWhereNotIn($column, $values);

        return $this;
    }

    /**
     * Order by column
     *
     * @param  string  $column
     * @param  OrderByDirectionEnum|null  $direction
     * @return static
     */
    public function orderBy(string $column, ?OrderByDirectionEnum $direction = null): self
    {
        $this->grammar->orderBy($column, $direction);

        return $this;
    }

    /**
     * Order by column desc
     *
     * @param  string  $column
     * @return static
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, OrderByDirectionEnum::Desc);
    }

    /**
     * Get the first record of the query.
     *
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function first(...$columns): ?Model
    {
        return $this->findOrderBy($columns, OrderByDirectionEnum::Asc);
    }

    /**
     * Get the last record of the query.
     *
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function last(...$columns): ?Model
    {
        return $this->findOrderBy($columns, OrderByDirectionEnum::Desc);
    }

    /**
     * Find a record by its primary key.
     *
     * @param  int  $id
     * @param  string[]  ...$columns
     * @return ?Model
     */
    public function find(int $id, ...$columns): ?Model
    {
        $this->grammar->where($this->model->primaryKey(), $id);

        return $this->first(...$columns);
    }

    /**
     * Get all records of the query.
     *
     * @param  string[]  ...$columns
     * @return Collection
     */
    public function get(...$columns): Collection
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        $statement = $this->prepareStatement()->fetchAll(PDO::FETCH_CLASS, $this->baseClass);

        $results = Collection::make($statement);

        if ($this->with) {
            $this->eagerRelationships($results);
        }

        return $results;
    }

    public function toSql(): string
    {
        return $this->grammar->toSql();
    }

    /**
     * Get the number of records of the query.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->prepareStatement()->rowCount();
    }

    /**
     * Prepare the statement.
     *
     * @return PDOStatement
     */
    private function prepareStatement(): PDOStatement
    {
        $pdo = $this->pdo;

        $statement = $pdo->prepare($this->grammar->toSql());

        $bindings = $this->resolveBindings($this->grammar->bindings());

        if ($statement->execute($bindings) === false) {
            throw new InvalidQueryException(
                sql: $this->grammar->toSql(),
                bindings: $bindings,
                message: $pdo->errorInfo()[2] ?? 'Unknown SQL error',
            );
        }

        return $statement;
    }

    /**
     * Find and order by a record.
     *
     * @param  string[]  $columns
     * @param  ?OrderByDirectionEnum  $direction
     * @return ?Model
     */
    private function findOrderBy(array $columns = [], ?OrderByDirectionEnum $direction = null): ?Model
    {
        if (count($columns)) {
            $this->grammar->select(...$columns);
        }

        /**
         * If an ORDER BY clause is defined, use its column; otherwise, fallback to the table's primary key.
         */
        $this->grammar->orderBy(
            $this->grammar->hasOrderBy()
                ? $this->grammar->getOrderByColumn()
                : $this->model->primaryKey(),
            $direction
        );

        $statement = $this->prepareStatement();

        $result = $statement->fetchObject($this->baseClass);

        return $result ? $result : null;
    }

    /**
     * Resolve the bindings.
     *
     * @param  array  $bindings
     * @return array
     */
    private function resolveBindings(array $bindings): array
    {
        return array_merge(...array_map(
            fn (mixed $value): array => is_array($value) ? $value : [$value],
            $bindings
        ));
    }

    private function eagerRelationships(Collection $models): void
    {
        foreach ($this->with as $relationName) {
            if ($models->isEmpty()) {
                continue;
            }

            $first = $models->first();
            $relation = $first->{$relationName}();

            match (true) {
                $relation instanceof HasOne => $this->eagerHasOne(
                    $models, $relationName, $relation
                ),

                $relation instanceof HasMany => $this->eagerHasMany(
                    $models, $relationName, $relation
                ),

                default => null,
            };
        }
    }

    /**
     * Eager load a HasOne relationship.
     *
     * @param  Collection<Model>  $models
     * @param  string  $relationName
     * @param  HasOne  $relation
     * @return void
     */
    private function eagerHasOne(Collection $models, string $relationName, HasOne $relation): void
    {
        $localKey = $relation->localKey();
        $foreign = $relation->foreignKey();
        $related = $relation->related();

        $keys = $models
            ->map(fn (Model $model) => $model->{$localKey})
            ->unique()
            ->toArray();

        /** @var Collection<Model> $results */
        $results = (new $related)::newQuery()->whereIn($foreign, $keys)->get();

        $dictionary = [];

        foreach ($results as $relatedModel) {
            $dictionary[$relatedModel->{$foreign}] = $relatedModel;
        }

        foreach ($models as $model) {
            $key = $model->{$localKey};
            $model->{$relationName} = $dictionary[$key] ?? null;
        }
    }

    /**
     * Eager load a HasMany relationship.
     *
     * @param  Collection<Model>  $models
     * @param  string  $relationName
     * @param  HasMany  $relation
     * @return void
     */
    private function eagerHasMany(Collection $models, string $relationName, HasMany $relation): void
    {
        $localKey = $relation->localKey();
        $foreign = $relation->foreignKey();
        $related = $relation->related();

        $keys = $models
            ->map(fn (Model $model) => $model->{$localKey})
            ->unique()
            ->values()
            ->toArray();

        /** @var Collection $results */
        $results = (new $related)::newQuery()->whereIn($foreign, $keys)->get();

        $grouped = [];

        foreach ($results as $result) {
            $grouped[$result->{$foreign}][] = $result;
        }

        foreach ($models as $model) {
            $key = $model->{$localKey};
            $model->{$relationName} = Collection::make($grouped[$key] ?? []);
        }
    }
}
