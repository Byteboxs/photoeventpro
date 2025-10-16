<?php

namespace app\core\database\builder;

use app\core\database\Database;

use app\core\Application;
use app\core\collections\Map;
use app\core\collections\ArrayList;

class BuilderOLD
{
    private $isDebugging = false;
    protected $parts;
    protected $posParams;
    protected $namedParams;
    protected $prepared = Application::RAW;
    protected $configuration;
    protected $table = '';
    protected $db;

    public function __construct(Database $db, $isDebugging = false, $prepared = Application::RAW)
    {
        $this->parts = new Map();
        $this->initParams();
        $this->configuration = new Map([
            "prepared" => $prepared
        ]);
        $this->db = $db;
        $this->isDebugging = $isDebugging;
    }

    public function sum($colName)
    {
        $result = $this->get()->fetchAll(\PDO::FETCH_ASSOC);
        $sum = 0;
        foreach ($result as $row) {
            $sum += $row[$colName];
        }
        return $sum;
    }

    private function initParams()
    {
        $this->posParams = new ArrayList();
        $this->namedParams = new Map();
    }
    private function config(array $options)
    {
        $this->configuration->merge(new Map($options));
    }

    public function setPreppared(int $prepared = Application::RAW)
    {
        $this->config(["prepared" => $prepared]);
    }

    /**
     * Select columns from the query.
     *
     * @param mixed ...$columns The columns to select.
     * @return self
     */
    public function select(mixed ...$columns): self
    {
        $select = null;
        $numArgs = count($columns);
        if ($numArgs === 1) {
            $value = $columns[0];
        } else if ($numArgs === 0) {
            $value = '*';
        } else {
            $value = $columns;
        }

        if ($this->parts->contains("SELECT")) {
            $selectParts = $this->parts->get("SELECT")->getValues();
            $select = $this->parts->get("SELECT");
            foreach ($selectParts as $part) {
                if (strval($part) === '*') {
                    $select = new Select();
                    break;
                }
            }
        } else {
            $select = new Select();
        }

        // $select = $this->parts->contains("SELECT") ? $this->parts->get("SELECT") : new Select();
        $select->addValue($value);
        $this->parts->add("SELECT", $select);

        return $this;
    }

    public function from(string $table)
    {

        // echo '<pre>Dentro de la funcion from</pre>';

        if ($this->parts->contains("FROM")) {
            $fromParts = $this->parts->get("FROM")->getValues();
            if (in_array($table, $fromParts)) {
                // echo '<pre>La tabla ya existe</pre>';
                $from = $this->parts->get("FROM");
                // $from->addValue($table);
            } else {
                $from = new From();
                $from->addValue($table);
            }
        } else {
            $from = new From();
            $from->addValue($table);
        }

        // $from = $this->parts->contains("FROM") ? $this->parts->get("FROM") : new From();
        // $from->addValue($table);
        $this->parts->add("FROM", $from);
        return $this;
    }

    /**
     * Adds a WHERE clause to the query.
     *
     * @param mixed ...$args The arguments for the WHERE clause.
     *                       If a single argument is provided and it is an array with 3 elements,
     *                       it will be treated as [name, operator, param].
     *                       If two arguments are provided, they will be treated as [name, param],
     *                       with the operator set to '='.
     *                       If three arguments are provided, they will be treated as [name, operator, param].
     * @return self The updated query object.
     * @throws \InvalidArgumentException If the number of arguments is not 2 or 3.
     */
    public function where(...$args): self
    {
        // echo '<pre>Dentro de la funcion where</pre>';
        $numArgs = count($args);
        // echo "<pre>numArgs: {$numArgs}</pre>";
        $value = null;
        $name = null;
        $operator = null;
        $param = null;
        if ($numArgs === 1) {
            $where = $args[0];
            if (is_array($where) && count($where) === 3) {
                [$name, $operator, $param] = $where;
            }
        } else if ($numArgs === 2) {
            [$name, $param] = $args;
            $operator = '=';
        } else if ($numArgs === 3) {
            [$name, $operator, $param] = $args;
        } else {
            throw new \InvalidArgumentException("Formato de argumentos no válido para el método where.");
        }

        $this->initParams();
        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $value = "{$name} {$operator} ?";
            $this->posParams->add($param);
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $posId = $name . uniqid();
            $value = "{$name} {$operator} :{$posId}";
            $this->namedParams->add($posId, $param);
        } else {
            $value = "{$name} {$operator} '{$param}'";
        }

        $where = $this->parts->contains("WHERE") ? $this->parts->get("WHERE") : new Where();
        $where->addValue($value);
        $this->parts->add("WHERE", $where);
        return $this;
    }

    private function in($column, array $values, $type = 'IN'): self
    {
        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $placeholders = implode(', ', array_fill(0, count($values), '?'));
            $value = "{$column} {$type} ({$placeholders})";
            $this->posParams->addArray($values);
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $arr = [];
            foreach ($values as $value) {
                $posId = 'in_' . uniqid();
                $this->namedParams->add($posId, $value);
                $arr[] = ":{$posId}";
            }
            $placeholders = implode(', ', $arr);
            $value = "{$column} {$type} ({$placeholders})";
        } else {
            $arr = [];
            foreach ($values as $value) {
                $arr[] = "'{$value}'";
            }
            $placeholders = implode(', ', $arr);
            $value = "{$column} {$type} ({$placeholders})";
        }

        $where = $this->parts->contains("WHERE") ? $this->parts->get("WHERE") : new Where();
        $where->addValue($value);
        $this->parts->add("WHERE", $where);
        return $this;
    }

    public function whereIn($column, array $values): self
    {
        return $this->in($column, $values, 'IN');
    }

    public function whereNotIn($column, array $values): self
    {
        return $this->in($column, $values, 'NOT IN');
    }


    public function like(string $column, string $value): self
    {
        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $this->posParams->add($value);
            $likeValue = '?';
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $likeValue = ':like_' . uniqid();
            $this->namedParams->add($likeValue, $value);
        } else {
            $likeValue = "'{$value}'";
        }

        $where = $this->parts->contains("WHERE") ? $this->parts->get("WHERE") : new Where();
        $where->addValue("{$column} LIKE {$likeValue}");
        $this->parts->add("WHERE", $where);
        return $this;
    }

    /**
     * Adds the DISTINCT modifier to the SELECT clause.
     *
     * @return self
     */
    public function distinct(): self
    {
        if ($this->parts->contains("SELECT")) {
            $select = $this->parts->get("SELECT");
            $select->addModifier("DISTINCT");
        }
        return $this;
    }

    public function offset(int $offset = 0, string $column = 'id'): self
    {
        if ($offset !== 0) {
            $this->where($column, '>', $offset);
        }
        return $this;
    }

    public function limit(int $limit = 0): void
    {
        if ($limit === 0) {
            return;
        }

        $limitStatement = $this->parts->contains("LIMIT") ? $this->parts->get("LIMIT") : new Limit();
        $limitStatement->addValue($limit);
        $this->parts->add("LIMIT", $limitStatement);
    }

    public function groupBy(...$args)
    {
        $numArgs = count($args);
        if ($numArgs === 1) {
            $limit = $args[0];
        } else {
            $limit = $args;
        }
        $groupBy = $this->parts->contains("GROUP_BY") ? $this->parts->get("GROUP_BY") : new GroupBy();
        $groupBy->addValue($limit);
        $this->parts->add("GROUP_BY", $groupBy);
        return $this;
    }

    public function orderBy(string $column, string $order = 'ASC'): self
    {
        $order = strtoupper($order); // Convertir a mayúsculas para hacer la comparación insensible a mayúsculas/minúsculas

        if ($order !== 'ASC' && $order !== 'DESC') {
            return $this;
        }

        $orderBy = $this->parts->contains("ORDER_BY") ? $this->parts->get("ORDER_BY") : new OrderBy();
        $orderBy->addValue($column . ' ' . $order);
        $this->parts->add("ORDER_BY", $orderBy);
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        $this->select()->from($table);
        return $this;
    }
    function debugPrint($message)
    {
        //     echo "<pre style='background-color: #f0f0f0; padding: 10px; margin: 5px 0; border: 1px solid #ddd;'>";
        //     echo htmlspecialchars($message);
        //     echo "</pre>";
    }

    public function insert(array $params)
    {
        $this->debugPrint('builder::insert');
        // var_dump($this->configuration->get("prepared"));
        $this->debugPrint('-');
        $insert = new Insert($this->table, $this->configuration->get("prepared"));
        $insert->addValue(json_encode($params));
        $this->parts->add("INSERT", $insert);

        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $this->posParams->addArray($insert->getBindParams());
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $this->namedParams->addArray($insert->getBindParams());
        }
        return $this->get();
    }

    public function insertGetId(array $params)
    {
        $this->insert($params);
        switch ($this->db->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
            case 'mysql':
                return $this->db->lastInsertId();
            case 'pgsql':
                return $this->db->lastInsertId($this->table . '_id_seq'); // Específico para PostgreSQL
            case 'sqlsrv':
                $stmt = $this->db->query("SELECT SCOPE_IDENTITY() AS id");
                return $stmt->fetch(\PDO::FETCH_ASSOC)['id'];
            default:
                // Manejar otros tipos de bases de datos si es necesario
                throw new \Exception("Tipo de base de datos no compatible");
        }
    }

    public function update(array $params)
    {
        $update = new Update($this->table, $this->configuration->get("prepared"));
        // var_dump(json_encode($params));
        $update->addValue(json_encode($params));
        $this->parts->add("UPDATE", $update);

        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $this->posParams->addArrayOnTop($update->getBindParams());
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $this->namedParams->addArray($update->getBindParams());
        }
        return $this->get();
    }

    public function delete()
    {
        $delete = new Delete($this->table);
        $this->parts->add("DELETE", $delete);
        $this->get();
    }

    public function join(string $table, string $column1, string $operator, string $column2)
    {
        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause();
        $join->join($this->table, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function leftJoin(string $table, string $column1, string $operator, string $column2)
    {
        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause(JoinClause::LEFT_JOIN);
        $join->join($this->table, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function rightJoin(string $table, string $column1, string $operator, string $column2)
    {
        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause(JoinClause::RIGHT_JOIN);
        $join->join($this->table, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function fullJoin(string $table, string $column1, string $operator, string $column2)
    {
        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause(JoinClause::FULL_JOIN);
        $join->join($this->table, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function toSql()
    {
        $parts = $this->parts->toArray();

        $sql = '';
        $sql .= $parts["SELECT"] ?? '';
        $sql .= $parts["FROM"] ?? '';
        $sql .= $parts["JOIN"] ?? '';
        $sql .= $parts["WHERE"] ?? '';
        $sql .= $parts["GROUP_BY"] ?? '';
        $sql .= $parts["ORDER_BY"] ?? '';
        $sql .= $parts["LIMIT"] ?? '';
        $sql = array_key_exists("INSERT", $parts) ? $parts["INSERT"]->getSql() : $sql;
        if (array_key_exists("UPDATE", $parts)) {
            $sql = $parts["UPDATE"];
            $sql .= $parts["WHERE"] ?? '';
        }
        if (array_key_exists("DELETE", $parts)) {
            $sql = $parts["DELETE"];
            $sql .= $parts["WHERE"] ?? '';
        }

        $sql = rtrim($sql, ' ') . ';';
        $this->parts->remove("SELECT");
        $this->parts->remove("JOIN");
        $this->parts->remove("WHERE");
        $this->parts->remove("GROUP_BY");
        $this->parts->remove("ORDER_BY");
        $this->parts->remove("LIMIT");
        $this->parts->remove("INSERT");
        $this->parts->remove("UPDATE");
        $this->parts->remove("DELETE");

        return $sql;
    }

    public function params(): ?array
    {
        $prepared = $this->configuration->get("prepared");
        if ($prepared === Application::PREPARED_POSITIONAL) {
            return $this->posParams->toArray();
        } elseif ($prepared === Application::PREPARED_NAMED) {
            return $this->namedParams->toArray();
        }
        return null;
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    public function commit(): void
    {
        $this->db->commit();
    }
    public function rollback(): void
    {
        $this->db->rollback();
    }
    public function transaction(\Closure $callback): void
    {
        $this->beginTransaction();
        try {
            $callback($this);
            $this->commit();
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function value($column)
    {
        $this->select($column);
        return $this->get()->fetchColumn();
    }

    public function first()
    {
        return $this->get()->fetch();
    }

    public function get(): \PDOStatement|bool
    {

        $sql = $this->toSql();
        // echo '<pre>';
        // var_dump($sql);
        // echo '</pre>';
        $params = $this->params();
        if ($this->isDebugging) {
            echo '<pre> ';
            echo '<br>builder::get<br>Sql: ';
            var_dump($sql);
            echo 'Parametros: ';
            if ($params) {
                var_dump($params);
            } else {
                echo 'no configurados';
            }
            echo '</pre>';
        }
        // return false;
        return $this->db->run($sql, $params);
    }
}
