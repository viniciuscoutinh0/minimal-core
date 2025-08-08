<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Grammar\Enums;

enum OperatorEnum: string
{
    case Equal = '=';

    case NotEqual = '<>';

    case GreaterThan = '>';

    case LessThan = '<';

    case GreaterThanOrEqual = '>=';

    case LessThanOrEqual = '<=';

    case Like = 'like';

    case NotLike = 'not like';

    case In = 'in';

    case NotIn = 'not in';

    case Between = 'between';

    case NotBetween = 'not between';
}
