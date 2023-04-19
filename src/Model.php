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

    public function save(): ?int
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $_id = null;
        $attributes = [];
        foreach ($this->fillable as $key) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $this->$key;
            }
        }

        if (isset($this->{$this->primaryKey})) {
            if ($this->timestamps) {
                $attributes['updated_at'] = date('Y-m-d H:i:s');
            }

            $queryBuilder->update($this->getTable())
                         ->set($attributes)
                         ->where($this->primaryKey, '=', $this->{$this->primaryKey})
                         ->execute();
            $_id = $this->{$this->primaryKey};
        } else {
            if ($this->timestamps) {
                $attributes['created_at'] = date('Y-m-d H:i:s');
                $attributes['updated_at'] = date('Y-m-d H:i:s');
            }

            $queryBuilder->insert($this->getTable())
                         ->values($attributes)
                         ->execute();

            $_id = $this->getConnection()->lastInsertId();
        }

        return $_id;
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
