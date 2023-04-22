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

}
