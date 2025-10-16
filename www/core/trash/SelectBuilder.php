<?php

namespace core\database;

class SelectBuilder
{
    protected $columns;
    protected $params;
    protected $distinct = false;
    protected $query = '';


    public function __construct(\core\collections\ArrayList $params)
    {
        $this->columns = new \core\collections\ArrayList();
        $this->params = $params;
    }

    public function select(...$columns): self
    {
        $numArgs = count($columns);
        if ($numArgs === 1) {
            if (is_array($columns[0])) {
                $this->columns->addArray($columns[0]);
            } else {
                $this->columns->add($columns[0]);
            }
        } else if ($numArgs === 0) {
            $this->columns->add("*");
        } else {
            $this->columns->addArray($columns);
        }
        return $this;
    }

    public function selectRaw(...$args)
    {
        $numArgs = count($args);
        if ($numArgs === 1) {
            $this->columns->add($args[0]);
        } else if ($numArgs === 2) {
            $this->columns->add($args[0]);
            if (is_array($args[1])) {
                foreach ($args[1] as $arg) {
                    $this->params->add($arg);
                }
            } else {
                $this->params->add($args[1]);
            }
        } else {
            throw new \InvalidArgumentException("El metodo selectRaw acepta 1 o 2 argumentos.");
        }
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function addSelect($query)
    {
        $sql = '';
        if (!$this->columns->isEmpty()) {
            if (stripos($query, 'SELECT') === false) {
                $sql .= "SELECT ";
            }
        }
        return $sql;
    }

    public function getSelectStatement()
    {
        $sql = '';
        if (!$this->columns->isEmpty()) {
            $sql .= implode(', ', $this->columns->toArray());
        }
        return $sql;
    }

    public function addDistinctStatement($sql)
    {
        if ($this->distinct) {
            // Añadir después de SELECT
            $sql = preg_replace('/^(SELECT\s)/i', '$1DISTINCT ', $sql, 1);
        } else {
            // Eliminar la palabra DISTINCT si existe
            $sql = preg_replace('/^(SELECT\sDISTINCT\s)/i', 'SELECT ', $sql, 1);
        }

        return $sql;
    }

    public function limit($limit)
    {
        return $this;
    }

    public function get(): string
    {
        $this->query .= $this->addSelect($this->query);
        $this->query .= $this->getSelectStatement();
        $this->query = $this->addDistinctStatement($this->query);
        return $this->query;
    }

    public function getParams(): array
    {
        return $this->params->toArray();
    }
}
