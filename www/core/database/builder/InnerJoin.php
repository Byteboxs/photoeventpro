<?php

namespace app\core\database\builder;


class InnerJoin extends Join
{
    public function __construct(string $table1, string $table2)
    {
        parent::__construct($table1, $table2);
        $this->joinType = ' INNER JOIN ';
    }
}
