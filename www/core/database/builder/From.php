<?php

namespace app\core\database\builder;

class From extends QueryPart
{
    private $from;

    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $this->sql = '';
        $this->from = implode(', ', $this->getValues());
        $this->sql .= "FROM {$this->from} ";
        return $this->sql;
    }

    public function getFrom()
    {
        $this->from = implode(', ', $this->getValues());
        return $this->from;
    }


    public function __toString()
    {
        return $this->getSql();
    }
}
