<?php

namespace app\core\database\blueprint;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Column
{
    private $name = '';
    private $newName = '';
    private $dataType = '';
    private $params;

    private $constraints;

    const NOT_NULL = 'NOT NULL';
    const UNIQUE = 'UNIQUE';
    const PRIMARY_KEY = 'PRIMARY KEY';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';
    const CHECH = 'CHECK';
    const COMMENT = 'COMMENT';
    const UNSIGNED = 'UNSIGNED';
    const DEFAULT = 'DEFAULT';
    const ON_UPDATE = 'ON UPDATE';

    private $change = false;
    private $add = false;
    private $drop = false;
    private $rename = false;


    public function __construct()
    {
        $this->constraints = new Map();
        $this->params = new ArrayList();
    }

    public function rename(string $name): Column
    {
        $this->newName = $name;
        $this->rename = true;
        return $this;
    }

    public function hasRename(): bool
    {
        return $this->rename;
    }

    public function change(): Column
    {
        $this->change = true;
        return $this;
    }

    public function add(): Column
    {
        $this->add = true;
        return $this;
    }

    public function drop(): Column
    {
        $this->drop = true;
        return $this;
    }

    public function hasDrop(): bool
    {
        return $this->drop;
    }

    public function hasAdd(): bool
    {
        return $this->add;
    }

    public function hasChange(): bool
    {
        return $this->change;
    }

    public function name(string $name): Column
    {
        $this->name = $name;
        return $this;
    }

    public function dataType(string $dataType): Column
    {
        $this->dataType = $dataType;
        return $this;
    }

    public function params(...$params): Column
    {
        $numArgs = count($params);
        if ($numArgs === 1) {
            $args = $params[0];
            if (is_array($args)) {
                $this->params->addArray($args);
            } else {
                $this->params->add($args);
            }
        } else if ($numArgs === 2) {
            $this->params->addArray($params);
        }
        return $this;
    }

    protected function constraints(...$params): void
    {
        $numArgs = count($params);
        if ($numArgs === 1) {
            $args = $params[0];
            $this->constraints->add($args, $args);
        } else if ($numArgs === 2) {
            $args = $params;
            $this->constraints->add($args[0], $args[1]);
        }
    }

    public function notNull(): Column
    {
        $this->constraints(self::NOT_NULL, true);
        return $this;
    }

    public function unique(): Column
    {
        if (!$this->constraints->contains(self::PRIMARY_KEY)) {
            $this->constraints(self::UNIQUE);
        }

        return $this;
    }

    public function autoIncrement(): Column
    {
        $this->constraints(self::AUTO_INCREMENT);
        return $this;
    }

    public function primary(): Column
    {
        if (!$this->constraints->contains(self::UNIQUE)) {
            $this->constraints(self::PRIMARY_KEY);
        }

        return $this;
    }

    public function check(string $constraint): Column
    {
        $this->constraints(self::CHECH, $constraint);
        return $this;
    }

    public function comment(string $comment): Column
    {
        $this->constraints(self::COMMENT, $comment);
        return $this;
    }

    public function default($default): Column
    {
        $this->constraints(self::DEFAULT, $default);
        return $this;
    }

    public function onUpdate($onUpdate): Column
    {
        $this->constraints(self::ON_UPDATE, $onUpdate);
        return $this;
    }

    public function unsigned(): Column
    {
        $this->constraints(self::UNSIGNED, true);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function getParams()
    {
        return $this->params->toArray();
    }

    public function getConstraints()
    {
        return $this->constraints->toArray();
    }

    public function hasParams()
    {
        return !$this->params->isEmpty();
    }

    public function hasConstraints()
    {
        return !$this->constraints->isEmpty();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string The string representation.
     */
    public function __toString(): string
    {
        $str = '';

        if ($this->hasRename()) {
            $str .= 'RENAME COLUMN ';
            return $str . $this->name . ' TO ' . $this->newName;
        }
        if ($this->hasChange()) {
            $str .= "CHANGE {$this->name} ";
        }
        if ($this->hasAdd()) {
            $str .= 'ADD ';
        }
        if ($this->hasDrop()) {
            $str .= 'DROP COLUMN ';
        }
        $str .= $this->name . ' ' . $this->dataType;

        if ($this->hasParams()) {
            $str .= '(' . implode(', ', $this->getParams()) . ')';
        }

        if ($this->hasConstraints()) {
            $iter = $this->constraints->getIterator();
            while ($iter->hasNext()) {
                $current = $iter->next();
                if ($current->key == self::CHECH) {
                    $str .= sprintf(' %s (%s)', $current->key, $current->value);
                } else if ($current->key == self::COMMENT) {
                    $str .= sprintf(" COMMENT '%s'", $current->value);
                } else if ($current->key == self::DEFAULT) {
                    $str .= sprintf(' %s %s', $current->key, $current->value);
                } else if ($current->key == self::ON_UPDATE) {
                    $str .= sprintf(' %s %s', $current->key, $current->value);
                } else {
                    $str .= ' ' . $current->key;
                }
            }
        }

        return $str;
    }
}
