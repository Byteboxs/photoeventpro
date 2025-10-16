<?php

namespace app\core\database\builder;

abstract class Join
{
    protected $table1;
    protected $table2;
    protected string $sql = '';
    protected string $table1Cname = '';
    protected string $table2Cname = '';
    protected string $operator = '';
    protected string $joinType = '';

    public function __construct(string $table1, string $table2)
    {
        $this->table1 = $table1;
        $this->table2 = $table2;
    }

    public function on(string $table1Cname, string $operator, string $table2Cname)
    {
        $this->table1Cname = $table1Cname;
        $this->operator = $operator;
        $this->table2Cname = $table2Cname;
        return $this;
    }

    public function render()
    {
        $sql = sprintf(
            "%s %s ON %s %s %s ",
            $this->joinType,
            $this->table2,
            $this->table1Cname,
            $this->operator,
            $this->table2Cname
        );
        return $sql;
    }

    public function __toString()
    {
        return $this->render();
    }
}
