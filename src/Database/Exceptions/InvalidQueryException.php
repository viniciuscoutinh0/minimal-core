<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Exceptions;

use Exception;

final class InvalidQueryException extends Exception
{
    public function __construct(
        public string $sql,
        public array $bindings,
        public string $message,
    ) {
        parent::__construct($message);
    }
}
