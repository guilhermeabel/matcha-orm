<?php

require_once __DIR__ . '\vendor\autoload.php';

use Examples\MatchaORM\User;

$defaultConnection = config('default');
$dbConfig = config('connections.' . $defaultConnection);

$connectionInstance = MatchaORM\Connection::getInstance($dbConfig);
$pdoConnection = $connectionInstance->getConnection();

// create a user
$user = new User();
$user->name = 'John Doe';
$user->email = 'johndoe@example.com' . rand(1, 100);
$user->save();

// update a user
$user = User::find(1);
$user->name = 'John Doe';
$user->email = 'johndoe@example.com' . rand(1, 100);
$user->password = '@%FX' . rand(1, 100);
$user->save();

// delete a user
$user = User::find(1);
$user->delete();


echo "<pre>";
var_dump($user);
