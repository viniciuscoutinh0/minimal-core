<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Request;

beforeEach(function (): void {
    $this->server = Request::make()->server();
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
