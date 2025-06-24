<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

final class Query
{
    public function __construct(private Connection $connection)
    {
    }
}
