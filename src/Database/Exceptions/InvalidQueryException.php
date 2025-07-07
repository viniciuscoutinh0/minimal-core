<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Exceptions;

use Exception;

final class InvalidQueryException extends Exception
{
    /**
     * SQL query
     *
     * @var string
     */
    protected string $sql;

    /**
     * Bindings for the query
     *
     * @var array
     */
    protected array $bindings = [];

    public function __construct(
         string $sql,
         array $bindings,
         string $message,
    ) {
        $this->sql = $sql;

        $this->bindings = $bindings;

        parent::__construct($message);
    }

    /**
     * Get the SQL query
     *
     * @return string
     */
    public function sql(): string
    {
        return $this->sql;
    }

    /**
     * Get the bindings for the query
     *
     * @return array
     */
    public function bindings(): array
    {
        return $this->bindings;
    }
}
