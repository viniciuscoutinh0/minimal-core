<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;

final class ServerBag
{
    public function __construct(private array $parameters = [])
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    public function method(): string
    {
        return $this->parameters['REQUEST_METHOD'] ?? throw new RuntimeException('Request Method is not defined');
    }

    public function uri(): string
    {
        return $this->parameters['REQUEST_URI'] ?? throw new RuntimeException('Request Uri is not defined');
    }

    public function all(): array
    {
        return $this->parameters;
    }
}
