<?php

namespace MatchaORM;

use MatchaORM\Drivers\MySqlConnection;
use PDO;

enum DriversEnum: string
{
    case MySql = "mysql";
    case PostgreSql = "pgsql";
}

class Connection extends PDO
{
    private static $instance = null;
    private $connection;
    private $required = ["DB_NAME", "DB_USER", "DB_DRIVER"];

    private function __construct()
    {
        foreach ($this->required as $key) {
            if (!defined($key)) {
                throw new \Exception("{$key} is not set.");
            }
        }

        $host = defined("DB_HOST") ? DB_HOST : "localhost";
        $charset = defined("DB_CHARSET") ? DB_CHARSET : "utf8mb4";

        try {
            match (DB_DRIVER) {
                DriversEnum::MySql->value => $this->connection = new MySqlConnection(DB_NAME, DB_USER, DB_PASS, $host, $charset),
                default => throw new \Exception("Driver not supported."),
            };
        } catch (PDOException $exception) {
            /** TO-DO: improve exception handling */
            throw new \Exception("Connection error: " . $exception->getMessage());
        }
    }

    public static function create(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
