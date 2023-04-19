<?php

require_once __DIR__ . '\vendor\autoload.php';

use Examples\MatchaORM\User;

$defaultConnection = config('default');
$dbConfig = config('connections.' . $defaultConnection);

$connectionInstance = MatchaORM\Connection::getInstance($dbConfig);
$pdoConnection = $connectionInstance->getConnection();

$user = new User();
echo "<pre>";
var_dump($user);
