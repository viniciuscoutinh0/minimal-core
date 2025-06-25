<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database;

use PDO;

final class QueryBuilder
{
    public function __construct(private PDO $pdo)
    {
    }
}
