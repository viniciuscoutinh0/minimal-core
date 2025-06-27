<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Concerns;

trait StaticConstruct
{
    /**
     * Create a new instance.
     *
     * @param  mixed  ...$args
     * @return static
     */
    public static function make(...$args): static
    {
        return new static(...$args);
    }
}
