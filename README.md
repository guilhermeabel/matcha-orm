# Matcha ORM 🚀 (Work in progress 🚧)

![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue?style=flat-square&logo=php)

Matcha ORM is a lightweight and flexible Object-Relational Mapping (ORM) library for PHP.
It provides an easy-to-use API for working with relational databases, enabling developers to interact with data through an object-oriented approach.

## Features ✨

- ✅ CRUD operations (Create, Read, Update, Delete)
- 🗺️ Mapping classes to database tables and properties to columns
- 🔍 Querying and filtering data
- 📦 ~~Migrations and schema management~~ (coming soon...)
- 🧑‍🤝‍🧑 ~~Relationships between entities (one-to-one, one-to-many, many-to-many)~~
- 🚛 ~~Lazy and eager loading~~
- 🔄 ~~Transactions and concurrency control~~

## Installation 📦

You can install Matcha ORM using Composer:

```composer require guilhermeabel/matcha-orm```

## Getting Started 🏁

To get started, create a `database.php` file to define your database connection settings:

```php
<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'your_database',
            'username' => 'your_username',
            'password' => 'your_password',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ],
    ],
];
```

Next, create a model class that extends the MatchaORM\Model class and maps to a database table:

```php
use MatchaORM\Model;

class User extends Model
{
 protected $fillable = ['name', 'email', 'password'];
}
```

Make sure to define the `$fillable` property with the columns that can be mass assigned.

Check to see if everything is working by creating a `index.php` file with the following code:

```php
<?php


require_once __DIR__ . '\vendor\autoload.php';

use Examples\MatchaORM\User;

// This will create a new instance for MatchaORM connection using the configuration file config/database.php
MatchaORM\Connection::getInstance(config('connections.' . config('default')));

```

Now you can perform CRUD operations and more with your User model:

```php
// Create a user
$user = new User();
$user->name = 'John Doe';
$user->email = 'johndoe@example.com';
$user->save();

// Update a user
$user = User::find(1);
$user->name = 'John Doe';
$user->email = 'johndoe@example.com' . rand(1, 100);
$user->save();

// Delete a user
$user = User::find(1);
$user->delete();

```
