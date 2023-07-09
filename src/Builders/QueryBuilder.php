<?php

namespace MatchaORM\Builders;

use PDO;

class QueryBuilder
{
    protected PDO $pdo;
    protected string $query;
    protected array $bindings = [];
    protected array $conditions = [];

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

    public function where(string $column): WhereBuilder
    {
        return new WhereBuilder($column, $this);
    }

    private function whereCondition(string $column, string $conditionType): WhereBuilder
    {
        return new WhereBuilder($column, $this, $conditionType);
    }

    /**
     * Add a condition to the query.
     *
     * @param string $conditionType
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function addCondition(string $conditionType, string $column, string $operator, $value): self
    {
        $placeholder = ':' . str_replace('.', '_', $column);
        $this->query .= (strpos($this->query, 'WHERE') !== false ? " {$conditionType}" : " WHERE") . " {$column} {$operator} {$placeholder}";
        $this->bindings[$placeholder] = $value;

        return $this;
    }

    public function and(string $column = null): WhereBuilder
    {
        return $this->whereCondition($column, 'AND');
    }

    public function or(string $column = null): WhereBuilder
    {
        return $this->whereCondition($column, 'OR');
    }

    /** PAGINATION */
    public function limit(int $limit): self
    {
        $this->query .= " LIMIT {$limit}";
        return $this;
    }

    public function offset(int $offset): self
    {
        if ($offset < 1) {
            return $this;
        }

        $this->query .= " OFFSET {$offset}";
        return $this;
    }

    public function paginate(int $perPage = 15, int $currentPage = 1): self
    {
        $offset = ($currentPage - 1) * $perPage;

        return $this->limit($perPage)->offset($offset);
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
        try {
            return $this->exec();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    protected function exec()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute($this->bindings);
        return $statement;
    }
}
