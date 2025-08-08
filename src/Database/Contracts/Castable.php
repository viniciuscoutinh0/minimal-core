<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Contracts;

interface Castable
{
    /**
     * Cast the given attribute value.
     *
     * @param  mixed  $value
     * @param  string|null  $attribute
     * @return mixed
     */
    public function cast(mixed $value, ?string $attribute = null): mixed;
}
