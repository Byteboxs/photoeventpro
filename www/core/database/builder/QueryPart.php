<?php

namespace app\core\database\builder;

use app\core\collections\ArrayList;

class QueryPart
{
    protected string $sql = '';
    protected $values;

    public function __construct()
    {
        $this->values = new ArrayList();
    }

    public function hasValues()
    {
        return !$this->values->isEmpty();
    }

    public function addValue($value)
    {
        if (is_array($value)) {
            $this->values->addArray($value);
        } else {
            $this->values->add($value);
        }
    }

    public function getValues()
    {
        return $this->values->toArray();
    }
}
