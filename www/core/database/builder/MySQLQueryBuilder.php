<?php

namespace app\core\database\builder;

use app\core\Application;
use app\core\collections\ArrayList;
use app\core\collections\Map;

class MySQLQueryBuilder implements QueryBuilderInterface
{
    private $isDebugging = false;
    protected $parts;
    protected $posParams;
    protected $namedParams;
    // protected $prepared = Application::RAW;
    protected $configuration;

    protected $sql = '';

    public function __construct()
    {
        $config = Application::$app->config;
        $prepared = $config->get("mysql")["prepared"];
        $this->parts = new Map();
        $this->initParams();
        $this->configuration = new Map([
            "prepared" => $prepared
        ]);
    }

    private function initParams()
    {
        $this->posParams = new ArrayList();
        $this->namedParams = new Map();
    }

    public function configPrepared(int $prepared = Application::RAW)
    {
        $this->configuration->merge(new Map(["prepared" => $prepared]));
    }

    public function select(mixed ...$columns): static
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

        $select->addValue($value);
        $this->parts->add("SELECT", $select);

        return $this;
    }
    public function from(string $table): static
    {
        if ($this->parts->contains("FROM")) {
            $fromParts = $this->parts->get("FROM")->getValues();
            if (in_array($table, $fromParts)) {
                $from = $this->parts->get("FROM");
            } else {
                $from = new From();
                $from->addValue($table);
            }
        } else {
            $from = new From();
            $from->addValue($table);
        }
        $this->parts->add("FROM", $from);
        return $this;
    }

    public function where(...$args): static
    {
        $numArgs = count($args);
        $value = null;
        $name = null;
        $operator = null;
        $param = null;
        if ($numArgs === 1) {
            $where = $args[0];
            if (is_array($where) && count($where) === 3) {
                [$name, $operator, $param] = $where;
            } else if (is_array($where) && count($where) === 2) {
                [$name, $param] = $where;
                $operator = '=';
            }
        } else if ($numArgs === 2) {
            [$name, $param] = $args;
            $operator = '=';
        } else if ($numArgs === 3) {
            [$name, $operator, $param] = $args;
        } else {
            throw new \InvalidArgumentException("Formato de argumentos no válido para el método where.");
        }

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
    public function in($column, array $values, $type = 'IN'): self
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

    public function limitOffset(int $limit, int $offset): void
    {
        $limitStatement = $this->parts->contains("LIMIT") ? $this->parts->get("LIMIT") : new Limit();
        $limitStatement->addValue($offset);
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

    public function insert(string $table, array $params)
    {
        // var_dump($params);
        $insert = new Insert($table, $this->configuration->get("prepared"));
        $insert->addValue(json_encode($params));
        $this->parts->add("INSERT", $insert);

        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $this->posParams->addArray($insert->getBindParams());
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $this->namedParams->addArray($insert->getBindParams());
        }
    }

    public function update(string $table, array $params)
    {
        $update = new Update($table, $this->configuration->get("prepared"));
        // var_dump(json_encode($params));
        $update->addValue(json_encode($params));
        $this->parts->add("UPDATE", $update);

        if ($this->configuration->get("prepared") === Application::PREPARED_POSITIONAL) {
            $this->posParams->addArrayOnTop($update->getBindParams());
        } else if ($this->configuration->get("prepared") === Application::PREPARED_NAMED) {
            $this->namedParams->addArray($update->getBindParams());
        }
        return $this;
    }

    public function delete(string $table): static
    {
        $delete = new Delete($table);
        $this->parts->add("DELETE", $delete);
        return $this;
    }

    protected function getFromTable(): string
    {
        $fromTable = null;
        if ($this->parts->contains("FROM")) {
            $fromTable = $this->parts->get("FROM")->getFrom();
        }

        return $fromTable;
    }

    public function join(string $table, string $column1, string $operator, string $column2)
    {
        $fromTable = $this->getFromTable();

        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause();
        $join->join($fromTable, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function leftJoin(string $table, string $column1, string $operator, string $column2)
    {
        $fromTable = $this->getFromTable();
        $join = $this->parts->contains("LEFT JOIN") ? $this->parts->get("LEFT JOIN") : new JoinClause(JoinClause::LEFT_JOIN);
        $join->join($fromTable, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("LEFT JOIN", $join);
        return $this;
    }

    public function rightJoin(string $table, string $column1, string $operator, string $column2)
    {
        $fromTable = $this->getFromTable();
        $join = $this->parts->contains("JOIN") ? $this->parts->get("JOIN") : new JoinClause(JoinClause::RIGHT_JOIN);
        $join->join($fromTable, $table)
            ->on($column1, $operator, $column2);
        $this->parts->add("JOIN", $join);
        return $this;
    }

    public function build(): string
    {
        $parts = $this->parts->toArray();
        $this->sql = '';
        $this->sql .= $parts["SELECT"] ?? '';
        $this->sql .= $parts["FROM"] ?? '';
        $this->sql .= $parts["JOIN"] ?? '';
        $this->sql .= $parts["LEFT JOIN"] ?? '';
        $this->sql .= $parts["WHERE"] ?? '';
        $this->sql .= $parts["GROUP_BY"] ?? '';
        $this->sql .= $parts["ORDER_BY"] ?? '';
        $this->sql .= $parts["LIMIT"] ?? '';
        $this->sql = array_key_exists("INSERT", $parts) ? $parts["INSERT"]->getSql() : $this->sql;
        if (array_key_exists("UPDATE", $parts)) {
            $this->sql = $parts["UPDATE"];
            $this->sql .= $parts["WHERE"] ?? '';
        }
        if (array_key_exists("DELETE", $parts)) {
            $this->sql = $parts["DELETE"];
            $this->sql .= $parts["WHERE"] ?? '';
        }
        $this->sql = rtrim($this->sql, ' ') . ';';
        $this->removeParts();

        return $this->sql;
    }
    private function removeParts()
    {
        $this->parts->remove("SELECT");
        $this->parts->remove("WHERE");
        $this->parts->remove("GROUP_BY");
        $this->parts->remove("ORDER_BY");
        $this->parts->remove("JOIN");
        $this->parts->remove("LEFT JOIN");
        $this->parts->remove("LIMIT");
        $this->parts->remove("INSERT");
        $this->parts->remove("UPDATE");
        $this->parts->remove("DELETE");
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
}
