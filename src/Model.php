<?php

namespace MatchaORM;

use MatchaORM\QueryBuilder;
use MatchaORM\Relations\{OneToOne, OneToMany, ManyToMany};

class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool $timestamps = true;
    protected array $fillable = [];
    protected array $guarded = [];
    protected QueryBuilder $queryBuilder;

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

    /**
     * Get the query builder instance or create a new one.
     *
     * @param Model|null $instance
     * @return QueryBuilder
     */
    protected function getQueryBuilder(?Model $instance = null): QueryBuilder
    {
        $instance = $instance ?? $this;

        $instance->queryBuilder ??= new QueryBuilder($instance->getConnection());

        return $instance->queryBuilder;
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
        $this->queryBuilder = $this->getQueryBuilder();

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

            $this->queryBuilder->update($this->getTable())
                         ->set($attributes)
                         ->where($this->primaryKey, '=', $this->{$this->primaryKey})
                         ->execute();
            $_id = $this->{$this->primaryKey};
        } else {
            if ($this->timestamps) {
                $attributes['created_at'] = date('Y-m-d H:i:s');
                $attributes['updated_at'] = date('Y-m-d H:i:s');
            }

            $this->queryBuilder->insert($this->getTable())
                         ->values($attributes)
                         ->execute();

            $_id = $this->getConnection()->lastInsertId();
        }

        return $_id;
    }

    public function delete()
    {
        $this->queryBuilder = $this->getQueryBuilder();

        if (isset($this->{$this->primaryKey})) {
            $this->queryBuilder = new QueryBuilder($this->getConnection());
            $this->queryBuilder->delete()
                         ->from($this->getTable())
                         ->where($this->primaryKey, '=', $this->{$this->primaryKey})
                         ->execute();
        }
    }

    public static function find($id)
    {
        $instance = new static();
        $queryBuilder = $instance->getQueryBuilder($instance);

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
        $queryBuilder = $instance->getQueryBuilder($instance);

        $records = $queryBuilder->select()
                                ->from($instance->getTable())
                                ->get();

        return array_map(fn ($record) => (new static())->fill($record), $records);
    }

    public static function get(): QueryBuilder
    {
        $instance = new static();
        $queryBuilder = $instance->getQueryBuilder($instance);
        return $queryBuilder->select()->from($instance->getTable());
    }

    public static function paginate(int $perPage = 15, int $currentPage = 1): array
    {
        $instance = new static();
        $queryBuilder = $instance->getQueryBuilder($instance);

        $offset = ($currentPage - 1) * $perPage;

        $records = $queryBuilder->select()
                                ->from($instance->getTable())
                                ->offset($offset)
                                ->limit($perPage)
                                ->get();

        return array_map(fn ($record) => (new static())->fill($record), $records);
    }

    public function hasOne(/* ... */)
    {
        return new OneToOne(/* ... */);
    }

    public function hasMany(/* ... */)
    {
        return new OneToMany(/* ... */);
    }
    public function belongsTo(/* ... */)
    {
        return new OneToOne(/* ... */);
    }
    public function belongsToMany(/* ... */)
    {
        return new ManyToMany(/* ... */);
    }

}
