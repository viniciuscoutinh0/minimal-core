<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Request;
use Viniciuscoutinh0\Minimal\Response;

if (! function_exists('request')) {
    function request(): Request
    {
        return Request::make();
    }
}

if (! function_exists('response')) {
    function response(): Response
    {
        return Response::make();
    }
}
