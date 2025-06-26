<?php

declare(strict_types=1);

use Viniciuscoutinh0\Minimal\Database\Grammar\Enums\OperatorEnum;
use Viniciuscoutinh0\Minimal\Database\Grammar\GrammarBuilder;

/** @property GrammarBuilder $grammar */
beforeEach(function (): void {
    $this->grammar = new GrammarBuilder;
});

it('generates a basic select query with specified columns', function (): void {
    $query = $this->grammar->table('users')->select('name', 'email')->toSql();

    expect($query)->toBe('select name, email from users');
});

it('adds distinct keyword to select query', function (): void {
    $query = $this->grammar->table('users')->distinct()->select('name', 'email')->toSql();

    expect($query)->toBe('select distinct name, email from users');
});

it('selects all columns when none are specified', function (): void {
    $query = $this->grammar->table('users')->toSql();

    expect($query)->toBe('select * from users');
});

it('adds single where condition to query', function (): void {
    $query = $this->grammar->table('users')->where('foo', 'bar')->toSql();

    expect($query)->toBe('select * from users where foo = ?');
});

it('adds multiple where conditions using and', function (): void {
    $query = $this->grammar->table('users')->where('foo', 'bar')->where('baz', 'qux')->toSql();

    expect($query)->toBe('select * from users where foo = ? and baz = ?');
});

it('adds orWhere condition to query', function (): void {
    $query = $this->grammar->table('users')->where('foo', 'bar')->orWhere('baz', 'qux')->toSql();

    expect($query)->toBe('select * from users where foo = ? or baz = ?');
});

it('generates where clause with LIKE operator', function (): void {
    $query = $this->grammar->table('users')->where('foo', '%bar%', OperatorEnum::Like)->toSql();

    expect($query)->toBe('select * from users where foo like ?');
});

it('returns where clause bindings', function (): void {
    $query = $this->grammar->table('users')->where('foo', '%bar%', OperatorEnum::NotLike)->bindings();

    expect($query)->toBe(['%bar%']);
});
