<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Concerns;

trait StaticConstruct
{
    public static function make(...$args): static
    {
        return new static(...$args);
    }
}
