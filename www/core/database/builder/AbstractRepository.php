<?php

namespace app\core\database\builder;

use app\core\Application;
use app\core\database\connection\ConnectionInterface;
use app\core\database\connection\ConnectionPool;
use app\core\exceptions\ModelNotFoundException;
use app\core\exceptions\RepositoryNotFoundException;

abstract class AbstractRepository implements RepositoryInterface
{
    protected ConnectionInterface $connection;
    protected QueryBuilderInterface $queryBuilder;
    protected string $table;
    protected string $className;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
    protected $fillable = [];
    protected $rules;

    public function __construct(ConnectionInterface $connection, QueryBuilderInterface $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
        if ($this->table === '') {
            $this->table = strtolower((new \ReflectionClass($this))->getShortName()) . 's';
        }
        $this->className = $this->getNombreClase($this->table);
    }

    private function getNombreClase($table)
    {
        $namespace = 'app\models';
        $table = strtolower($table);
        $primeraLetra = strtoupper(substr($table, 0, 1));
        return $namespace . '\\' . $primeraLetra . substr($table, 1);
    }

    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    private function get()
    {
        return $this->connection->run($this->queryBuilder->build(), $this->queryBuilder->params());
    }

    public function all(): array
    {
        $this->queryBuilder->select('*')->from($this->table);
        $stmt = $this->get();
        return $stmt?->fetchAll() ?: [];
    }

    public function find(int|string $id): mixed
    {
        $this->queryBuilder
            ->select('*')
            ->from($this->table)
            ->where([$this->primaryKey, $id]);
        $stmt = $this->get();
        return $stmt?->fetch() ?: throw new RepositoryNotFoundException("Busqueda sin resultados");
    }

    public function findWhere(array $data)
    {
        $query = $this->queryBuilder
            ->select('*')
            ->from($this->table);
        if (is_array($data)) {
            foreach ($data as $value) {
                $query->where($value);
            }
        }
        $stmt = $this->get();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        return $stmt?->fetch() ?: throw new RepositoryNotFoundException("Busqueda sin resultados");
    }

    public function create(array $data): mixed
    {
        $fillableAttributes = array_intersect_key($data, array_flip($this->fillable));
        $this->queryBuilder->insert($this->table, $fillableAttributes);
        return $this->connection->run($this->queryBuilder->build(), $this->queryBuilder->params());
    }

    public function update($data): mixed
    {
        if (isset($data[$this->primaryKey])) {
            $id = $data[$this->primaryKey];
            unset($data[$this->primaryKey]);
        }

        $fillableAttributes = array_intersect_key($data, array_flip($this->fillable));
        $this->queryBuilder
            ->update($this->table, $fillableAttributes)
            ->where([$this->primaryKey, $id]);
        return $this->connection->run($this->queryBuilder->build(), $this->queryBuilder->params());
    }

    public function delete($data): mixed
    {
        if (isset($data[$this->primaryKey])) {
            $id = $data[$this->primaryKey];
            unset($data[$this->primaryKey]);
        }
        $this->queryBuilder
            ->delete($this->table)
            ->where([$this->primaryKey, $id]);
        return $this->connection->run($this->queryBuilder->build(), $this->queryBuilder->params());
    }
}
