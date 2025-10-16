<?php

namespace core\trash;

class Builder
{
    protected $fromStatement;
    // protected $columns;
    protected $distinct = false;
    protected $select = false;
    protected $where = false;
    protected $limitStatement = '';
    protected $orderByStatement = '';
    protected $orderByParams;
    // protected $andConditions;
    protected $params;
    protected $query = '';

    protected $selectBuilder;
    protected $whereBuilder;
    private $db;

    private $isInsert = false;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->db = \core\database\Database::getInstance();
        $this->fromStatement = null;
        $this->params = new \core\collections\ArrayList();
        $this->orderByParams = new \core\collections\ArrayList();
        $this->selectBuilder = new \core\database\SelectBuilder($this->params);
        $this->whereBuilder = new \core\database\WhereBuilder($this->params);
    }
    public function select(...$columns)
    {
        $this->selectBuilder->select(...$columns);
        $this->select = true;
        return $this;
    }

    public function selectRaw(...$args)
    {
        $this->select = true;
        $this->selectBuilder->selectRaw(...$args);
        return $this;
    }

    public function distinct()
    {
        $this->selectBuilder->distinct();
        return $this;
    }

    public function getSelectStatement()
    {
        return $this->selectBuilder->getSelectStatement();
    }

    protected function addDistinctStatement($sql)
    {
        return $this->selectBuilder->addDistinctStatement($sql);
    }

    public function from($from)
    {
        $this->fromStatement = $from;
        return $this;
    }

    public function where(...$args)
    {
        $this->whereBuilder->where(...$args);
        $this->where = true;
        return $this;
    }
    public function whereColumn(...$args)
    {
        $this->whereBuilder->whereColumn(...$args);
        $this->where = true;
        return $this;
    }

    public function whereRaw(...$args)
    {
        $this->whereBuilder->whereRaw(...$args);
        $this->where = true;
        return $this;
    }

    public function whereIn($column, $data)
    {
        $this->whereBuilder->whereIn($column, $data);
        $this->where = true;
        return $this;
    }

    public function whereNotIn($column, $data)
    {
        $this->whereBuilder->whereNotIn($column, $data);
        $this->where = true;
        return $this;
    }

    private function addWhere($query)
    {
        $this->where = true;
        return $this->whereBuilder->addWhere($query);
    }

    public function getWhereStatement()
    {
        return $this->whereBuilder->getWhereStatement();
    }

    private function addFrom()
    {
        if ($this->fromStatement != null) {
            return " FROM ";
        }
    }

    public function getFromStatement()
    {
        $sql = '';
        $sql .= $this->fromStatement;
        return $sql;
    }

    public function limit(int $limit = 0, int $offset = 0): self
    {
        if ($limit === 0) {
            return $this;
        }

        if ($offset !== 0) {
            $this->where = true;
            $this->whereBuilder->where('id', '>', $offset);
        }

        $this->limitStatement = ' LIMIT ' . $limit;

        return $this;
    }

    public function orderBy($column = '', $order = 'ASC')
    {
        if ($column == '') {
            return $this;
        }

        $this->orderByParams->add([$column, $order]);
        return $this;
    }

    private function getOrderBy()
    {
        $params = $this->orderByParams->toArray();
        if (count($params) === 0) {
            return '';
        }
        $orderByStatement = implode(', ', array_map(function ($orderBy) {
            return $orderBy[0] . ' ' . $orderBy[1];
        }, $params));

        return  ' ORDER BY ' . $orderByStatement;
    }

    public function getSql(): string
    {
        if ($this->isInsert) {
            return '';
        }

        $this->query .= $this->select === true ? 'SELECT ' : '';
        $this->query .= $this->selectBuilder->getSelectStatement();
        $this->query = $this->selectBuilder->addDistinctStatement($this->query);
        $this->query .= $this->addFrom();
        $this->query .= $this->getFromStatement();
        $this->query .= $this->where === true ? ' WHERE ' : '';
        $this->query .= $this->whereBuilder->getWhereStatement();
        $this->query .= $this->getOrderBy();
        $this->query .= $this->limitStatement;

        return $this->query;
    }

    public function getParams(): array
    {
        return $this->params->toArray();
    }

    public function insert($params)
    {
        try {
            $this->isInsert = true;
            $columns = array_keys($params);
            $bindParams = array_values($params);
            $sql = 'INSERT INTO ' . $this->getFromStatement() . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_fill(0, count($columns), '?')) . ')';
            $this->db->run($sql, $bindParams);
        } catch (\PDOException $e) {
            echo 'Error al ejecutar la consulta: ' . $e->getMessage();
        }
    }

    public function get()
    {
        $sql = $this->getSql();
        $params = $this->getParams();
        echo '<pre>';
        var_dump($sql, $params);
        echo '</pre>';
        return $sql != '' ? $this->db->run($sql, $params) : null;
    }
}
