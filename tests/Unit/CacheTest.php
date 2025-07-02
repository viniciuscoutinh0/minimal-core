<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Factory\CacheFactory;

beforeEach(function (): void {
    $this->cache = CacheFactory::create();

    $this->cache->flush();
});

it('can add cache item', function (): void {
    $this->cache->put('foo', fn () => 'bar', 1500);

    expect($this->cache->get('foo'))->toBe('bar');
});

it('can remove cache item', function (): void {
    $this->cache->put('foo', fn () => 'bar', 1500);
    $this->cache->put('baz', fn () => 'qux', 1500);

    $this->cache->forget('foo');

    expect($this->cache->get('foo'))->toBeNull();
    expect($this->cache->get('baz'))->toBe('qux');
});

it('does not execute the callback if item exists in cache', function (): void {
    $this->cache->put('expensive', fn () => 'from cache', 1500);

    $called = false;

    $result = $this->cache->remember('expensive', function () use (&$called) {
        $called = true;

        return 'should not run';
    }, 1500);

    expect($called)->toBeFalse();
    expect($result)->toBe('from cache');
});

it('can flush the cache', function (): void {
    $this->cache->put('foo', fn () => 'bar', 1500);
    $this->cache->put('baz', fn () => 'qux', 1500);

    $this->cache->flush();

    expect($this->cache->get('foo'))->toBeNull();
    expect($this->cache->get('baz'))->toBeNull();
});
