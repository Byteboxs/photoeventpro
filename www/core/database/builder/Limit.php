<?php

namespace app\core\database\builder;

class Limit extends QueryPart
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $this->sql = '';
        $limit = implode(', ', $this->getValues());
        $this->sql .= "LIMIT {$limit} ";
        return $this->sql;
    }

    public function __toString()
    {
        return  $this->getSql();
    }
}
