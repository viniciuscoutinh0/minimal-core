<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Database\Concerns;

use BackedEnum;
use Carbon\Carbon;

trait HasCastAttribute
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected array $casts = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return $this->casts;
    }

    /**
     * Cast an attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function castAttribute(string $key): mixed
    {
        $value = $this->attributes[$key];

        if ($value === null) {
            return null;
        }

        $type = $this->casts[$key];

        if (enum_exists($type) && is_subclass_of($type, BackedEnum::class)) {
            return $type::from($value);
        }

        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => (bool) $value,
            'string' => (string) $value,
            'array' => is_string($value) ? json_decode($value, true) : (array) $value,
            'json' => is_string($value) ? json_decode($value, true) : $value,
            'datetime' =>  $value instanceof Carbon ? $value : Carbon::createFromFormat('Y-m-d H:i:s', $value),
            'date' => $value instanceof Carbon ? $value : Carbon::createFromFormat('Y-m-d', $value),
            default => $value,
        };
    }
}
