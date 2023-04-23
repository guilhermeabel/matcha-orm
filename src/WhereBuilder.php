<?php

namespace MatchaORM;

class WhereBuilder
{
    protected string $column;
    protected QueryBuilder $queryBuilder;
    protected string $nextConditionType = 'AND';

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
        $this->queryBuilder->addCondition($conditionType, $column, $operator, $value);
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

    /** OTHER */

    public function in(array $values): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'IN', $values);
        return $this->queryBuilder;
    }

    public function notIn(array $values): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'NOT IN', $values);
        return $this->queryBuilder;
    }

    public function isNull(): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'IS NULL', null);
        return $this->queryBuilder;
    }

    public function isNotNull(): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'IS NOT NULL', null);
        return $this->queryBuilder;
    }

    public function between($value1, $value2): QueryBuilder
    {
        $this->queryBuilder->add($this->nextConditionType, $this->column, 'BETWEEN', [$value1, $value2]);
        return $this->queryBuilder;
    }

}
