<?php

namespace app\core\database\builder;

class GroupBy extends QueryPart
{

    private $modifiers = [];
    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $columns = implode(', ', $this->getValues());
        $this->sql .= "GROUP BY {$columns} ";
        return $this->sql;
    }

    public function __toString()
    {
        return  $this->getSql();
    }
}
