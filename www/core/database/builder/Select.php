<?php

namespace app\core\database\builder;

class Select extends QueryPart
{

    private $modifiers = [];
    public function __construct()
    {
        parent::__construct();
    }

    public function getSql()
    {
        $this->sql = '';
        $columns = implode(', ', $this->getValues());
        $modifiers = implode(' ', $this->modifiers);
        $this->sql .= "SELECT{$modifiers} {$columns} ";
        return $this->sql;
    }

    public function addModifier($modifier)
    {
        $this->modifiers[] = $modifier;
    }

    public function __toString()
    {
        return  $this->getSql();
    }
}
