<?php

require_once __DIR__ . '\vendor\autoload.php';

define('DB_NAME', 'matcha');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');

use Examples\MatchaORM\User;

// // INSERT
// $newUser = new User();
// $newUser->name = 'John Doe';
// $newUser->email = 'johndoe'.rand(1, 10000).'@example.com';
// $newUser->save();

// // SELECT with conditions
// $users = User::select()
//             ->where('name')
//             ->equal('John Doe')
//             ->get();

// UPDATE
$user = User::find(1);
$user->name = 'Jane Doe';
$user->save();

// DELETE
$user = User::find(1);
$user->delete();

// SELECT with LIKE
$users = User::select()
            ->where('name')
            ->like('%John%')
            ->get();

// SELECT with NOT LIKE
$users = User::select()
            ->where('name')
            ->notLike('%John%')
            ->get();

// // SELECT with IN
// $users = User::select()
//             ->where('name')
//             ->in(["John Doe", 20, 25])
//             ->get();

// // SELECT with NOT IN
// $users = User::select()
//             ->where('name')
//             ->notIn([18, 20, 25])
//             ->get();

// // SELECT with IS NULL
// $users = User::select()
//             ->where('deleted_at')
//             ->isNull()
//             ->get();

// // SELECT with IS NOT NULL
// $users = User::select()
//             ->where('deleted_at')
//             ->isNotNull()
//             ->get();

// SELECT with pagination
$users = User::select()
            ->paginate(20, 3)
            ->get();

echo "<pre>";
var_dump($user);
