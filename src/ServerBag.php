<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use RuntimeException;

final class ServerBag
{
    public function __construct(private array $parameters = [])
    {
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
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Get the request method.
     *
     * @return string
     * @throws RuntimeException
     */
    public function method(): string
    {
        return $this->parameters['REQUEST_METHOD'] ?? throw new RuntimeException('Request Method is not defined');
    }

    /**
     * Get the request uri.
     *
     * @return string
     * @throws RuntimeException
     */
    public function uri(): string
    {
        return $this->parameters['REQUEST_URI'] ?? throw new RuntimeException('Request Uri is not defined');
    }

    /**
     * Returns is request is POST.
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
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
}
