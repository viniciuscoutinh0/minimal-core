<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Contracts;

use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

interface QueryInterface
{
    /**
     * Get a new query builder instance.
     *
     * @return GrammarBuilder
     */
    public function query(): GrammarBuilder;
}
