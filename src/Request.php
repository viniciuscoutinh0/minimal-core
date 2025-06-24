<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class Request
{
    use StaticConstruct;

    public function __construct(
        private array $get,
        private array $post,
        private array $server,
        private array $cookies,
        private array $files,
    ) {
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
