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

To get started, you have to define your database connection constants:

```php
<?php

define('DB_NAME', 'matcha');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');

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

MatchaORM will automatically create a new instance for connection using the database configuration constants defined earlier.

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
