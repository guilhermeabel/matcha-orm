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

    //** TO-DO: join, subqueries, transaction support */

    /** CREATE */

    public function insert(string $table): self
    {
        $this->query = "INSERT INTO {$table}";
        return $this;
    }

    public function values(array $data): self
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn ($col) => ':' . $col, $columns);

        $this->query .= ' (' . implode(', ', $columns) . ')';
        $this->query .= ' VALUES (' . implode(', ', $placeholders) . ')';

        foreach ($data as $column => $value) {
            $this->bindings[':' . $column] = $value;
        }

        return $this;
    }

    /** READ */

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

    //** UPDATE */

    public function where(string $column, string $operator, $value): self
    {
        $placeholder = ':' . str_replace('.', '_', $column);
        $this->query .= " WHERE {$column} {$operator} {$placeholder}";
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, $value): self
    {
        $this->query .= " OR {$column} {$operator} ?";
        $this->bindings[] = $value;
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
