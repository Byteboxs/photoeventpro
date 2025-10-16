<?php

namespace core\database;

class Schema
{
    private $table;
    private $db;
    private $builder;

    public function __construct()
    {
        $this->table = null;
        $this->builder = new \core\database\Builder();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function get()
    {
        $this->builder = new \core\database\Builder();
        $this->builder
            ->select()
            ->from($this->table)->orderBy('id')->orderBy('correo');
        return $this->builder->get()->fetchAll();
    }

    public function where(...$args)
    {
        $this->builder = new \core\database\Builder();
        $this->builder->where(...$args);
        return $this;
    }

    public function value(...$columns)
    {
        $this->builder->select($columns)->from($this->table);
        return $this->builder->get()->fetchAll();
    }

    public function first()
    {
        $this->builder->select()->from($this->table)->orderBy('id')->limit(1);
        return $this->builder->get()->fetchAll();
    }

    public function insert($params)
    {
        $this->builder = new \core\database\Builder();
        $this->builder->from($this->table)->insert($params);
    }
}
