# Matcha ORM 🚀

![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue?style=flat-square&logo=php)

Matcha ORM is a lightweight and flexible Object-Relational Mapping (ORM) library for PHP.
It provides an easy-to-use API for working with relational databases, enabling developers to interact with data through an object-oriented approach.

## Features ✨

- ✅ CRUD operations (Create, Read, Update, Delete)
- 🗺️ Mapping classes to database tables and properties to columns
- 🧑‍🤝‍🧑 Relationships between entities (one-to-one, one-to-many, many-to-many)
- 🔍 Querying and filtering data
- 🔄 Transactions and concurrency control
- 📦 Migrations and schema management
- 🚛 Lazy and eager loading

## Installation 📦

You can install Matcha ORM using Composer:

```composer require guilhermeabel/matcha-orm```

## Getting Started 🏁

To get started, create a `config.php` file to define your database connection settings:

```php
return [
    'driver'   => 'mysql',
    'host'     => 'localhost',
    'database' => 'your_database',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset'  => 'utf8',
    'collation'=> 'utf8_general_ci',
    'prefix'   => '',
];
```

Next, create a model class that extends the MatchaORM\Model class and maps to a database table:

```php
use MatchaORM\Model;

class User extends Model
{
    protected $table = 'users';
}
```

Now you can perform CRUD operations and more with your User model:

```php
// Create a new user
$user = new User();
$user->name = 'John Doe';
$user->email = 'john.doe@example.com';
$user->save();

// Retrieve a user by ID
$user = User::find(1);

// Update a user
$user->email = 'johndoe@example.com';
$user->save();

// Delete a user
$user->delete();

// Query users with filters
$users = User::where('email', 'like', '%example.com%')->get();
```
