<?php

namespace MatchaORM;

use MatchaORM\Drivers\MySqlConnection;
use PDO;

enum DriversEnum
{
    case MySql;
    case PostgreSql;
}

class Connection extends PDO
{
    private static $instance = null;
    private $connection;
    private $host = "localhost";
    private $charset = "utf8mb4";

    private function __construct()
    {
        $database = $_ENV["DB_NAME"];
        $username = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASS"];
        $driver = $_ENV["DB_DRIVER"];

        if (empty($database) || empty($username) || empty($password) || empty($driver)) {
            throw new \Exception("Database configuration is missing, verify your environment variables.");
        }

        $host = $_ENV["DB_HOST"] ?: $this->host;
        $charset = $_ENV["DB_CHARSET"] ?: $this->charset;

        try {
            match ($driver) {
                DriversEnum::MySql => $this->connection = new MySqlConnection($host, $database, $username, $password, $charset),
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
