<?php

declare(strict_types=1);

if (! function_exists('vite')) {
    function vite(string $path): string
    {
        return (new Viniciuscoutinh0\Minimal\Vite)->assetUrl($path);
    }
}
