<?php

namespace MatchaORM;

class WhereBuilder
{
    protected $column;
    protected $queryBuilder;

    public function __construct(string $column, QueryBuilder $queryBuilder)
    {
        $this->column = $column;
        $this->queryBuilder = $queryBuilder;
    }

    public function and(string $column = null): self
    {
        if ($column !== null) {
            $this->currentColumn = $column;
        }
        $this->nextConditionType = 'AND';
        return $this;
    }

    public function or(string $column = null): self
    {
        if ($column !== null) {
            $this->currentColumn = $column;
        }
        $this->nextConditionType = 'OR';
        return $this;
    }

    protected function add(string $conditionType, string $column, string $operator, $value): self
    {
        $this->queryBuilder->_addWhere($conditionType, $column, $operator, $value);
        $this->nextConditionType = 'AND';

        return $this;
    }
}
