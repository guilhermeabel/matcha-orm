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

    /** COMPARISON */

    public function greaterThan($value): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, '>', $value);
        return $this->queryBuilder;
    }

    public function greaterThanOrEqual($value): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, '>=', $value);
        return $this->queryBuilder;
    }

    public function lessThan($value): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, '<', $value);
        return $this->queryBuilder;
    }

    public function lessThanOrEqual($value): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, '<=', $value);
        return $this->queryBuilder;
    }

    public function notEqual($value): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, '<>', $value);
        return $this->queryBuilder;
    }

    /** LIKE */

    public function like($pattern): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'LIKE', $pattern);
        return $this->queryBuilder;
    }

    public function notLike($pattern): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'NOT LIKE', $pattern);
        return $this->queryBuilder;
    }
}
