<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Illuminate\Support\Collection;
use Viniciuscoutinh0\Minimal\Concerns\When;

final class InputBag
{
    use When;

    public function __construct(private array $parameters = [])
    {
        //
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Get all parameters as collection.
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return collect($this->all());
    }

    /**
     * Get a parameter.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->parameters[$key];

        return $value ? $this->sanitizeInput($value) : $default;
    }

    /**
     * Set a parameter.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Check if a parameter exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Get the expected keys.
     *
     * @param  string[]  $keys
     * @return array
     */
    public function except(string ...$keys): array
    {
        return array_filter(
            $this->parameters,
            fn (string $key): bool => ! in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the only specified keys.
     *
     * @param  string[]  $keys
     * @return array
     */
    public function only(string ...$keys): array
    {
        return array_filter(
            $this->parameters,
            fn (string $key): bool => in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get all keys.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * Get all values.
     *
     * @return array
     */
    public function values(): array
    {
        return array_values($this->parameters);
    }

    /**
     * Returns sanitized value.
     *
     * @return string
     */
    private function sanitizeInput(string $value): string
    {
        $value = strip_tags($value);

        return trim($value);
    }
}
