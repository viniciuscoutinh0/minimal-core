<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Request;

beforeEach(function (): void {
    $request = new Request(
        $_GET,
        $_POST,
        $_SERVER,
        $_COOKIE,
    );

    $this->server = $request->server();
});

it('returns all server parameters', function (): void {
    expect($this->server->all())->toBe($_SERVER);
});

it('retuns request method', function (): void {
    $server = new Request([], [], ['REQUEST_METHOD' => 'GET'], [], []);

    expect($server->server()->method())->toBe('GET');
});

it('throws exception if request method not defined', function (): void {
    $this->server->method();
})->throws(RuntimeException::class);
