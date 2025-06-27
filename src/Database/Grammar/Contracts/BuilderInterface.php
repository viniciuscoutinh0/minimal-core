<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Contracts;

interface BuilderInterface
{
    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function toSql(): string;

    /**
     * Get the bindings for the query.
     *
     * @return array
     */
    public function bindings(): array;
}
