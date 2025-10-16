<?php

namespace app\core\database\builder;

class Where extends QueryPart
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $this->sql = '';
        $conditions = implode(' AND ', $this->getValues());
        $this->sql .= "WHERE {$conditions} ";
        return $this->sql;
    }

    public function __toString()
    {
        return  $this->getSql();
    }
}
