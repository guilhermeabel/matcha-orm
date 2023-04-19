<?php

namespace MatchaORM;

use MatchaORM\QueryBuilder;

class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $timestamps = true;
    protected $fillable = [];
    protected $guarded = [];

    public function __construct()
    {
        $this->table = $this->getTableName();
    }

    protected function getConnection()
    {
        $defaultConnection = config('default');
        $dbConfig = config('connections.' . $defaultConnection);

        return (Connection::getInstance($dbConfig))->getConnection();
    }

    protected function getTable()
    {
        return $this->table;
    }

    private function getTableName()
    {
        $className = get_class($this);
        $className = str_replace('\\', '/', $className);
        $className = basename($className);
        $tableName = strtolower($className) . 's';

        return $tableName;
    }


    protected function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        return $this;

    }

    public static function find($id)
    {
        $instance = new static();
        $queryBuilder = new QueryBuilder($instance->getConnection());

        $record = $queryBuilder->select()
                               ->from($instance->getTable())
                               ->where($instance->getPrimaryKey(), '=', $id)
                               ->first();

        if ($record) {
            $instance->fill($record);
            return $instance;
        }

        return null;
    }


    public static function all(): array
    {
        $instance = new static();
        $queryBuilder = new QueryBuilder($instance->getConnection());
        $records = $queryBuilder->select()
                                ->from($instance->getTable())
                                ->get();

        return array_map(fn ($record) => (new static())->fill($record), $records);
    }

}
