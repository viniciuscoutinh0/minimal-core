<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Concerns;

trait When
{
    /**
     * Conditional execution.
     *
     * @param  mixed  $value
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static
     */
    public function when(mixed $value, callable $callback, ?callable $default = null): static
    {
        if ($value) {
            return $callback($this, $value) ?? $this;
        }

        if ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }
}
