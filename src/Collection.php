<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

final class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    public function __construct(
        protected array $items = []
    ) {
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get all of the items in the collection as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Transform the collection into a JSON string.
     *
     * @return string
     */
    public function toJson(int $flags = 0): string
    {
        return json_encode($this->items, $flags | JSON_THROW_ON_ERROR);
    }

    /**
     * Filter the items in the collection.
     *
     * @return static
     */
    public function filter(Closure $callback, bool $preserveKeys = false): static
    {
        $filter = array_filter($this->items, $callback);

        return new self(
            $preserveKeys ? array_values($filter) : $filter
        );
    }

    /**
     * Apply a callback to each item in the collection.
     *
     * @return static
     */
    public function map(Closure $callback): static
    {
        return new self(
            array_map($callback, $this->items)
        );
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param  Closure  $callback
     * @param  mixed  $initial
     * @return mixed
     */
    public function reduce(Closure $callback, mixed $initial = null): mixed
    {
        $result = $initial;

        foreach ($this as $key => $value) {
            $result = $callback($result, $value, $key);
        }

        return $result;
    }

    /**
     * Traverse each item in the collection.
     *
     * @param  Closure  $callback
     * @return static
     */
    public function each(Closure $callback): static
    {
        foreach ($this as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }

    /**
     * Get all of the values in the collection.
     *
     * @return static
     */
    public function values(): static
    {
        return new self(
            array_values($this->items)
        );
    }

    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse(): static
    {
        $results = [];

        foreach ($this->items as $key => $values) {
            if ($values instanceof self) {
                $values = $values->all();
            } elseif (! is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return new self(array_merge([], ...$results));
    }

    /**
     * Check if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Check if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Get the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Add an item to the collection.
     *
     * @param  mixed  $item
     * @return static
     */
    public function push(mixed $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Add an item to the collection.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return static
     */
    public function put(mixed $key, mixed $value): static
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Get an item from the collection.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function get(mixed $key): mixed
    {
        return $this->offsetGet($key);
    }

    /**
     * Check if an item exists in the collection.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function has(mixed $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Get all of the keys in the collection.
     *
     * @return static
     */
    public function keys(): static
    {
        return new static(array_keys($this->items));
    }

    /**
     * Get an iterator for the items in the collection.
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get especific item from the collection.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Add an item to the collection.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset !== null) {
            $this->items[$offset] = $value;
        } else {
            $this->items[] = $value;
        }
    }

    /**
     * Remove an item from the collection.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Check if an item exists in the collection.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Convert the collection to its JSON representation.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->items;
    }
}
