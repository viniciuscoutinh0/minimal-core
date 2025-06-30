<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Request;

beforeEach(function (): void {
    $this->request = Request::make();
});

it('set query string parameter', function (): void {
    $this->request->query()->set('foo', 'bar');

    expect($this->request->query()->get('foo'))->toBe('bar');
});

it('returns all query string parameters', function (): void {
    $this->request->query()->set('foo', 'bar');

    expect($this->request->query()->all())->toBe(['foo' => 'bar']);
});

it('only returns specified query string parameter', function (): void {
    $this->request->query()->set('foo', 'bar');
    $this->request->query()->set('baz', 'qux');

    expect($this->request->query()->only('foo'))->toBe(['foo' => 'bar']);
});

it('excludes specified query string parameter', function (): void {
    $this->request->query()->set('foo', 'bar');
    $this->request->query()->set('baz', 'qux');

    expect($this->request->query()->except('foo'))->toBe(['baz' => 'qux']);
});

it('returns all query string keys', function (): void {
    $this->request->query()->set('foo', 'bar');
    $this->request->query()->set('baz', 'qux');

    expect($this->request->query()->keys())->toBe(['foo', 'baz']);
});

it('conditionally returns query string parameter', function (): void {
    $this->request->query()->when(true, function ($request): void {
        $request->set('foo', 'bar');
    });

    expect($this->request->query()->get('foo'))->toBe('bar');
});

it('unless conditionally returns query string parameter', function (): void {
    $this->request->query()->when(false, function ($request): void {
        $request->set('foo', 'bar');
    });

    expect($this->request->query()->get('foo'))->toBeNull();
});
