<?php

namespace MatchaORM\Drivers;

use MatchaORM\Connection;
use PDO;

class MySqlConnection extends Connection
{
    private $driver = DriverEnum::MYSQL;

    public function __construct(string $host, string $database, string $username, string $password, string $charset)
    {
        $dsn = "{$this->driver}:host={$host};dbname={$database};charset={$charset}";
        $connection = new PDO($dsn, $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
