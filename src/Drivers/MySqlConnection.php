<?php

namespace MatchaORM\Drivers;

use MatchaORM\Connection;
use PDO;

class MySqlConnection extends PDO
{
    public function __construct(string $database, string $username, string $password, string $host = "localhost", string $charset = "utf8mb4")
    {
        $driver = \MatchaORM\DriversEnum::MySql->value;
        $dsn = "{$driver}:host={$host};dbname={$database};charset={$charset}";

        parent::__construct($dsn, $username, $password);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
}
