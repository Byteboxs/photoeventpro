<?php

namespace app\core\database\blueprint;

class Primary
{
    private $add = false;
    private $drop = false;
    private $primary = '';

    public function __construct($primary)
    {
        $this->primary = $primary;
    }

    public function add()
    {
        $this->add = true;
    }
    public function hasAdd(): bool
    {
        return $this->add;
    }

    public function drop()
    {
        $this->drop = true;
    }

    public function hasDrop(): bool
    {
        return $this->drop;
    }

    public function __toString()
    {
        if ($this->hasAdd()) {
            return sprintf("ADD PRIMARY KEY(%s)", $this->primary);
        } else if ($this->hasDrop()) {
            return sprintf("DROP PRIMARY KEY");
        } else {
            return sprintf(
                "PRIMARY KEY (%s)",
                $this->primary
            );
        }
    }
}
