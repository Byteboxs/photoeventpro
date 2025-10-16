<?php

namespace app\core\database\builder;

use app\core\Application;
use app\core\exceptions\InvalidBuilderException;
use app\core\exceptions\ModelNotFoundException;
use app\core\exceptions\ModelValidationException;

class Builder
{
    private $connection;
    private $queryBuilder;
    private $logger;

    public function __construct($connection, $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->connection = $connection;
        $this->logger = Application::$app->logger;
    }
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }
    public function commit(): void
    {
        $this->connection->commit();
    }
    public function rollback(): void
    {
        $this->connection->rollback();
    }
    public function transaction(\Closure $callback, bool $throwException = true)
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (ModelNotFoundException $e) {
            $this->rollback();
            // $this->debugPrint($e->getMessage());
            $this->logger->log('error', $e->getMessage());
            return null;
        } catch (\Exception $e) {
            $this->rollback();
            // $this->debugPrint($e->getMessage());
            $this->logger->log('error', $e->getMessage());
            return null;
        } catch (ModelValidationException $e) {
            $this->rollback();
            // $this->debugPrint($e->getMessage());
            $this->logger->log('error', $e->getMessage());
            return null;
        } catch (InvalidBuilderException $e) {
            $this->rollback();
            // $this->debugPrint($e->getMessage());
            $this->logger->log('error', $e->getMessage());
            return null;
        }
    }

    public function table($table)
    {
        $this->queryBuilder->select('*')->from($table);
        return $this;
    }

    public function select(...$columns)
    {
        $this->queryBuilder->select(...$columns);
        return $this;
    }

    public function from($table)
    {
        $this->queryBuilder->from($table);
        return $this;
    }

    public function where(...$args)
    {
        $this->queryBuilder->where(...$args);
        return $this;
    }

    public function in($column, array $values, $type = 'IN'): self
    {
        $this->queryBuilder->in($column, $values, $type);
        return $this;
    }
    public function whereIn($column, array $values): self
    {
        $this->queryBuilder->whereIn($column, $values);
        return $this;
    }

    public function whereNotIn($column, array $values): self
    {
        $this->queryBuilder->whereNotIn($column, $values);
        return $this;
    }

    public function like(string $column, string $value): self
    {
        $this->queryBuilder->like($column, $value);
        return $this;
    }

    public function distinct(): self
    {
        $this->queryBuilder->distinct();
        return $this;
    }
    public function offset(int $offset = 0, string $column = 'id'): self
    {
        $this->queryBuilder->offset($offset, $column);
        return $this;
    }

    public function limit(int $limit = 0): void
    {
        $this->queryBuilder->limit($limit);
    }

    public function limitOffset(int $limit, int $offset): void
    {
        $this->queryBuilder->limitOffset($limit, $offset);
    }

    public function groupBy(...$args): self
    {
        $this->queryBuilder->groupBy(...$args);
        return $this;
    }

    public function orderBy(string $column, string $order = 'ASC'): self
    {
        $this->queryBuilder->orderBy($column, $order);
        return $this;
    }

    public function insert(string $table, array $params): self
    {
        $this->queryBuilder->insert($table, $params);
        return $this;
    }

    public function update(string $table,  int|string $id, array $data, string $idName = 'id'): self
    {
        $this->queryBuilder->update($table, $data)->where([$idName, $id]);
        return $this;
    }

    public function delete(string $table): self
    {
        $this->queryBuilder->delete($table);
        return $this;
    }

    public function join(string $table, string $column1, string $operator, string $column2): self
    {
        $this->queryBuilder->join($table, $column1, $operator, $column2);
        return $this;
    }

    public function leftJoin(string $table, string $column1, string $operator, string $column2): self
    {
        $this->queryBuilder->leftJoin($table, $column1, $operator, $column2);
        return $this;
    }

    public function rightJoin(string $table, string $column1, string $operator, string $column2): self
    {
        $this->queryBuilder->rightJoin($table, $column1, $operator, $column2);
        return $this;
    }

    public function get()
    {
        $sql = $this->queryBuilder->build();
        $params = $this->queryBuilder->params();
        $this->debugPrint($sql);
        if ($params) {
            $this->debugPrint(is_array($params) ? implode(', ', $params) : '');
        }
        return $this->connection->run($sql, $params);
    }

    public function run($sql, $params = null): ?\PDOStatement
    {
        return $this->connection->run($sql, $params);
    }

    protected function debugPrint($message)
    {
        $isDebugging = Application::$app->config->get('debug');
        if ($isDebugging) {
            // echo "<pre style='background-color: #f0f0f0; padding: 10px; margin: 5px 0; border: 1px solid #ddd;'>";
            // print_r($message);
            // echo "</pre>";
            $this->logger->log('debug', $message);
        }
    }
}
