<?php

namespace core\trash;

class InsertBuilder
{
    private $tableName;
    private $params;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function insert(array $params)
    {
        foreach ($params as $key => $value) {
        }
    }
}
