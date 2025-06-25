<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

use Viniciuscoutinh0\Minimal\Concerns\StaticConstruct;

final class Request
{
    use StaticConstruct;

    private InputBag $query;

    private InputBag $request;

    private ServerBag $server;

    private InputBag $cookie;

    public function __construct(
         array $get,
         array $post,
         array $server,
         array $cookies,
    ) {
        $this->query = new InputBag($get);

        $this->request = new InputBag($post);

        $this->server = new ServerBag($server);

        $this->cookie = new InputBag($cookies);
    }

    public function query(): InputBag
    {
        return $this->query;
    }

    public function request(): InputBag
    {
        return $this->request;
    }

    public function server(): ServerBag
    {
        return $this->server;
    }

    public function cookie(): InputBag
    {
        return $this->cookie;
    }
}
