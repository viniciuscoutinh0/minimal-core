<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Illuminate\Support\Collection;

final class InputBag
{
    public function __construct(private array $parameters = [])
    {
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function toCollection(): Collection
    {
        return collect($this->all());
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function except(string ...$keys): array
    {
        return array_filter(
            $this->parameters,
            fn (string $key): bool => ! in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }

    public function only(string ...$keys): array
    {
        return array_filter(
            $this->parameters,
            fn (string $key): bool => in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }

    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    public function values(): array
    {
        return array_values($this->parameters);
    }
}
