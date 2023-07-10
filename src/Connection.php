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
    private $host = 'localhost';
    private $charset = 'utf8mb4';

    private function __construct()
    {
        $database = getenv('MATCHA_DATABASE');
        $username = getenv('MATCHA_USERNAME');
        $password = getenv('MATCHA_PASSWORD');
        $driver = getenv('MATCHA_DRIVER');

        if ($database === false || $username === false || $password === false || $driver === false) {
            throw new \Exception('Database configuration is missing, verify your environment variables.');
        }

        $host = getenv('MATCHA_HOST') ?: $this->host;
        $charset = getenv('MATCHA_CHARSET') ?: $this->charset;

        try {
            match ($driver) {
                DriversEnum::MySql => $this->connection = new MySqlConnection($host, $database, $username, $password, $charset),
                default => throw new \Exception('Driver not supported.'),
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
