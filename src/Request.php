<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal;

final readonly class Request
{
    /**
     * InputBag with GET parameters.
     *
     * @var InputBag
     */
    private InputBag $query;

    /**
     * InputBag with POST parameters.
     *
     * @var InputBag
     */
    private InputBag $input;

    /**
     * ServerBag with SERVER parameters.
     *
     * @var ServerBag
     */
    private ServerBag $server;

    /**
     * InputBag with COOKIE parameters.
     *
     * @var InputBag
     */
    private InputBag $cookie;

    public function __construct(
        array $get,
        array $post,
        array $server,
        array $cookies,
    ) {
        $this->query = new InputBag($get);

        $this->input = new InputBag($post);

        $this->server = new ServerBag($server);

        $this->cookie = new InputBag($cookies);
    }

    /**
     * Create a new request instance.
     *
     * @return Request
     */
    public static function make(): self
    {
        return new self($_GET, $_POST, $_SERVER, $_COOKIE);
    }

    /**
     * Get query parameters.
     *
     * @return InputBag
     */
    public function query(): InputBag
    {
        return $this->query;
    }

    /**
     * Get request parameters.
     *
     * @return InputBag
     */
    public function input(): InputBag
    {
        return $this->input;
    }

    /**
     * Get server parameters.
     *
     * @return ServerBag
     */
    public function server(): ServerBag
    {
        return $this->server;
    }

    /**
     * Get cookie parameters.
     *
     * @return InputBag
     */
    public function cookie(): InputBag
    {
        return $this->cookie;
    }
}
