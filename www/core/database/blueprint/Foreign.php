<?php

namespace app\core\database\blueprint;

class Foreign
{
    private $foreignKey = '';
    private $references = '';
    private $on = '';
    private $add = false;
    private $drop = false;

    public function __construct($foreignKey)
    {
        $this->foreignKey = $foreignKey;
    }
    public function references($references): self
    {
        $this->references = $references;
        return $this;
    }

    public function on($on): self
    {
        $this->on = $on;
        return $this;
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
            return sprintf(
                "ADD CONSTRAINT fk_%s_foreign FOREIGN KEY (%s) REFERENCES %s(%s)",
                $this->foreignKey,
                $this->foreignKey,
                $this->on,
                $this->references,
            );
        } else if ($this->hasDrop()) {
            return sprintf(
                "DROP FOREIGN KEY fk_%s_foreign",
                $this->foreignKey
            );
        } else {
            return sprintf(
                "CONSTRAINT fk_%s_foreign FOREIGN KEY (%s) REFERENCES %s(%s)",
                $this->foreignKey,
                $this->foreignKey,
                $this->on,
                $this->references
            );
        }
    }
}
