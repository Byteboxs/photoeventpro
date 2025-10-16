<?php

namespace app\core\database\builder;

class OrderBy extends QueryPart
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $this->sql = '';
        $columns = implode(', ', $this->getValues());
        $this->sql .= "ORDER BY {$columns} ";
        return $this->sql;
    }

    public function __toString()
    {
        return  $this->getSql();
    }
}
