<?php

namespace MatchaORM;

use PDO;

class QueryBuilder
{
    protected $pdo;
    protected $query;
    protected $bindings = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(string $columns = '*'): self
    {
        $this->query = "SELECT {$columns}";
        return $this;
    }

    public function from(string $table): self
    {
        $this->query .= " FROM {$table}";
        return $this;
    }

    //** TO-DO: join, insert, subqueries, transaction support */

    public function get()
    {
        $stmt = $this->execute();
        return $stmt->fetchAll();
    }

    public function first()
    {
        $stmt = $this->execute();
        return $stmt->fetch();
    }

    protected function execute()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->bindings);
        return $stmt;
    }
}
