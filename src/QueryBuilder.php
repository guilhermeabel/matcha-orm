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

    public function update(string $table): self
    {
        $this->query = "UPDATE {$table}";
        return $this;
    }

    public function set(array $data): self
    {
        $set = [];
        foreach ($data as $column => $value) {
            $placeholder = ':' . $column;
            $set[] = "{$column} = {$placeholder}";
            $this->bindings[$placeholder] = $value;
        }
        $this->query .= ' SET ' . implode(', ', $set);
        return $this;
    }


    /** DELETE */

    public function delete(): self
    {
        $this->query = "DELETE ";
        return $this;
    }

    /** CONDITIONS */
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

    /** OPERATIONS */

    public function get()
    {
        $statement = $this->exec();
        return $statement->fetchAll();
    }

    public function first()
    {
        $statement = $this->exec();
        return $statement->fetch();
    }

    public function execute()
    {
        return $this->exec();
    }

    protected function exec()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute($this->bindings);
        return $statement;
    }
}
