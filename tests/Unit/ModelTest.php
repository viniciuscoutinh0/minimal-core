<?php

use Carbon\Carbon;
use Viniciuscoutinh0\Minimal\Collection;
use Viniciuscoutinh0\Minimal\Database\Model;
use Viniciuscoutinh0\Minimal\Factory\DatabaseConnectionFactory;

beforeAll(function (): void {
    $connection = DatabaseConnectionFactory::create();

    $pdo = $connection->pdo();

    $pdo->exec(<<<SQL
        CREATE TABLE IF NOT EXISTS users (
            id          INTEGER PRIMARY KEY AUTOINCREMENT, 
            name        VARCHAR(255), 
            email       VARCHAR(255), 
            password    VARCHAR(255),
            data        VARCHAR(255) default '{"key": "value"}',
            status      INT DEFAULT 1,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )

    SQL);


    $pdo->exec(<<<SQL
        INSERT INTO users (name, email, password)
        VALUES ('John Doe', 'V2i0F@example.com', 'password')
    SQL);
});

afterAll(function (): void {
    $connection = DatabaseConnectionFactory::create();

    $pdo = $connection->pdo();

    $pdo->exec('DROP TABLE IF EXISTS users');
});

beforeEach(function (): void {
    $this->user = new class extends Model {
        protected string $table = 'users';

        protected array $casts = [
            'data' => 'array',
            'created_at' => 'datetime',
            'status' => 'int',
        ];
    };
});

it('can create a new model instance', function (): void {
    expect($this->user)->toBeInstanceOf(Model::class);
});

it('can get the table associated with the model', function (): void {
    expect($this->user->table())->toBe('users');
});

it('can get the primary key associated with the table', function (): void {
    expect($this->user->primaryKey())->toBe('id');
});

it('can get first record from database', function (): void {
    $user = $this->user::newQuery()->first();

    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('V2i0F@example.com');
    expect($user->password)->toBe('password');
});

it('can cast value attribute', function (): void {
    $user = $this->user::newQuery()->first();

    expect($user->data)->toBeArray();
    expect($user->data['key'])->toBe('value');

    expect($user->status)->toBe(1);
    expect($user->status)->toBeInt();

    expect($user->created_at)->toBeInstanceOf(Carbon::class);
});

it('can get all records from database', function (): void {
    $users = $this->user::newQuery()->get();

    expect($users->count())->toBe(1);

    expect($users)->toBeInstanceOf(Collection::class);
});

it('can find record by id', function (): void {
    $user = $this->user::newQuery()->find(1);

    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('V2i0F@example.com');
    expect($user->password)->toBe('password');
});
