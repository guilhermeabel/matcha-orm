<?php

require_once __DIR__ . '\vendor\autoload.php';

use Examples\MatchaORM\User;

$defaultConnection = config('default');
$dbConfig = config('connections.' . $defaultConnection);

$connectionInstance = MatchaORM\Connection::getInstance($dbConfig);
$pdoConnection = $connectionInstance->getConnection();

// INSERT
$newUser = new User();
$newUser->name = 'John Doe';
$newUser->email = 'johndoe@example.com' . rand(1, 100);
$newUser->save();

// SELECT with conditions
$users = User::get()
             ->where('age')
             ->greaterThan(18)
             ->and('gender')
             ->equal('male')
             ->get();

// UPDATE
$user = User::find(1);
$user->name = 'Jane Doe';
$user->save();

// DELETE
$user = User::find(1);
$user->delete();

// SELECT with LIKE
$users = User::get()
             ->where('name')
             ->like('%John%')
             ->get();

// SELECT with NOT LIKE
$users = User::get()
             ->where('name')
             ->notLike('%John%')
             ->get();

// SELECT with IN
$users = User::get()
             ->where('age')
             ->in([18, 20, 25])
             ->get();

// SELECT with NOT IN
$users = User::get()
             ->where('age')
             ->notIn([18, 20, 25])
             ->get();

// SELECT with IS NULL
$users = User::get()
             ->where('deleted_at')
             ->isNull()
             ->get();

// SELECT with IS NOT NULL
$users = User::get()
             ->where('deleted_at')
             ->isNotNull()
             ->get();

// SELECT with BETWEEN
$users = User::get()
             ->where('age')
             ->between(18, 25)
             ->get();

// SELECT with pagination
$users = User::get()
             ->paginate(10, 1);


echo "<pre>";
var_dump($users);
