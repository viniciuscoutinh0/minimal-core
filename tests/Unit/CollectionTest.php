<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Collection;

it('returns all collection items', function (): void {
    $collect = new Collection(['foo' => 'bar', 'baz' => 'qux']);

    expect($collect->all())->toBe([
        'foo' => 'bar',
        'baz' => 'qux',

    ]);
});

it('can transform collection in json', function (): void {
    $collect = new Collection(['foo' => 'bar']);

    expect($collect->toJson())->toBe('{"foo":"bar"}');
});

it('can apply filters collection items', function (): void {
    $collect = new Collection([10, 20, 30]);

    expect($collect->filter(fn ($item) => $item > 20)->values()->all())->toBe([30]);
});

it('can apply a callback to each item in the collection', function (): void {
    $collect = new Collection([10, 20, 30]);

    expect($collect->map(fn ($item) => $item * 2)->all())->toBe([20, 40, 60]);
});

it('returns all values numeric indexed after filter', function (): void {
    $collect = new Collection([10, 20, 30, 40]);

    expect($collect->filter(fn ($item) => $item > 20)->values()->all())->toBe([30, 40]);
});

it('collapses nested collections into a single flat array', function (): void {
    $collect = new Collection([
        [1, 2, 3, 4, 5],
        new Collection([6, 7, 8, 9, 10]),
    ]);

    expect($collect->collapse()->all())->toBe([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
});

it('can verify if collection is empty', function (): void {
    $collect = new Collection([]);

    expect($collect->isEmpty())->toBeTrue();
});

it('can verify if collection is not empty', function (): void {
    $collect = new Collection([1, 2, 3]);

    expect($collect->isNotEmpty())->toBeTrue();
});

it('can push item to collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    $collect->push(4);

    expect($collect->all())->toBe([1, 2, 3, 4]);
});

it('can put item to collection', function (): void {
    $collect = new Collection(['foo' => 'bar']);

    $collect->put('baz', 'qux');

    expect($collect->all())->toBe([
        'foo' => 'bar',
        'baz' => 'qux',
    ]);
});

it('can add item with ArrayAccess', function (): void {
    $collect = new Collection([1, 2, 3]);
    $collect[3] = 4;

    expect($collect->all())->toBe([1, 2, 3, 4]);
});

it('can remove item from collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    $collect->offsetUnset(1);

    expect($collect->values()->all())->toBe([1, 3]);
});

it('can get item from collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    expect($collect->get(1))->toBe(2);
});

it('can get first item from collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    expect($collect->get(0))->toBe(1);
});

it('can each item in collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    $items = [];

    $collect->each(function ($item) use (&$items): void {
        $items[] = $item;
    });

    expect($items)->toBe([1, 2, 3]);
});

it('returns keys from collection', function (): void {
    $collect = new Collection(['foo' => 'bar', 'baz' => 'qux']);

    expect($collect->keys()->all())->toBe(['foo', 'baz']);
});

it('can reduce collection', function (): void {
    $collect = new Collection([1, 2, 3]);

    expect($collect->reduce(fn ($carry, $item) => $carry + $item))->toBe(6);
});

it('can unique values from collection', function (): void {
    $collect = new Collection([1, 2, 3, 3, 4]);

    expect($collect->unique()->all())->toEqual([1, 2, 3, 4]);
});

it('can sum array values', function (): void {
    $collect = Collection::make([1, 2, 3]);

    $collectB = Collection::make([4, 5, 6])->push(7)->map(fn ($i): int => $i * 2);

    expect($collect->sum())->toBe(6);
    expect($collectB->sum())->toBe(44);
});

it('can sum array values with callback', function (): void {
    $collect = Collection::make([1, 2, 3]);

    expect($collect->sum(fn ($i): int => $i * 2))->toBe(12);
});

it('can avg array values', function (): void {
    $collect = Collection::make([1, 2, 3]);

    expect($collect->avg())->toBe(2);
});

it('can average array values with callback', function (): void {
    $collect = Collection::make([1, 2, 3]);

    expect($collect->avg(fn ($i): int => $i * 2))->toBe(4);
});
