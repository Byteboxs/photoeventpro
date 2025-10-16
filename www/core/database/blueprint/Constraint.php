<?php

namespace app\core\database\blueprint;

class Constraint
{
    private $PRIMARY_KEY = false;
    private $UNIQUE = false;
    private $FOREIGN_KEY = false;

    private $constraint = null;
    private $change = false;
    private $add = false;
    public function __construct()
    {
    }

    public function change()
    {
        $this->change = true;
    }
    public function hasChange(): bool
    {
        return $this->change;
    }

    public function add()
    {
        $this->add = true;
    }

    public function hasAdd(): bool
    {
        return $this->add;
    }

    public function foreign(string $column = ''): Foreign
    {
        $this->FOREIGN_KEY = true;
        $foreign = new Foreign($column);
        $this->constraint = $foreign;
        return $foreign;
    }

    public function primary($column = ''): Primary
    {
        $this->PRIMARY_KEY = true;
        $primary = new Primary($column);
        $this->constraint = $primary;
        return $primary;
    }

    public function __toString()
    {
        $s = '';
        if ($this->FOREIGN_KEY == true) {
            $s = $this->constraint;
        } else if ($this->PRIMARY_KEY == true) {
            $s = $this->constraint;
        }

        return $s;
    }
}
