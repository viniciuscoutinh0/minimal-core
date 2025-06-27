<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Request;
use Viniciuscoutinh0\Minimal\Response;

if (! function_exists('request')) {
    /**
     * Request instance.
     *
     * @return Request
     */
    function request(): Request
    {
        return Request::make();
    }
}

if (! function_exists('response')) {
    /**
     * Response instance.
     *
     * @return Response
     */
    function response(): Response
    {
        return Response::make();
    }
}

if (! function_exists('view')) {
    /**
     * View instance.
     *
     * @return Viniciuscoutinh0\Minimal\View\View
     */
    function view(string $view, array $data = []): Viniciuscoutinh0\Minimal\View
    {
        return Viniciuscoutinh0\Minimal\View::make($view, $data);
    }
}

if (! function_exists('render')) {
    /**
     * Render a view.
     *
     * @param  string  $name
     * @return void
     */
    function render(string $name): void
    {
        Viniciuscoutinh0\Minimal\View::render($name);
    }
}
