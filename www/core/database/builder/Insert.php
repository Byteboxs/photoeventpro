<?php

namespace app\core\database\builder;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Insert extends QueryPart
{
    private $prepared = self::RAW;
    private $table;
    const PREPARED_POSITIONAL = 1;
    const PREPARED_NAMED = 2;
    const RAW = 3;
    protected $posParams;
    protected $namedParams;

    public function __construct(string $table, int $prepared = self::RAW)
    {
        parent::__construct();
        $this->namedParams = new Map();
        $this->posParams = new ArrayList();
        $this->prepared = $prepared;
        $this->table = $table;
    }

    public function init()
    {
        $this->sql = '';
        $params = json_decode($this->getValues()[0], true);
        $columns = '';
        $values = '';

        if ($this->prepared == self::RAW) {
            foreach ($params as $key => $value) {
                $columns .= ", $key";
                if (is_null($value)) {
                    $value = 'NULL';
                    $values .= ", $value";
                } else {
                    $values .= ", '$value'";
                }
            }
        } else if ($this->prepared == self::PREPARED_POSITIONAL) {
            foreach ($params as $key => $value) {
                $columns .= ", $key";
                $values .= ", ?";
                $this->posParams->add($value);
            }
        } else if ($this->prepared == self::PREPARED_NAMED) {
            $columns = '';
            $values = '';
            foreach ($params as $key => $value) {
                $columns .= ", $key";
                $posId = $key;
                $values .= ", :$posId";
                $this->namedParams->add($posId, $value);
            }
        }
        $columns = ltrim($columns, ', ');
        $values = ltrim($values, ', ');

        $this->sql .= "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
    }

    public function getBindParams()
    {
        $this->init();
        if ($this->prepared == self::RAW) {
            return null;
        } else if ($this->prepared == self::PREPARED_POSITIONAL) {
            return $this->posParams->toArray();
        } else if ($this->prepared == self::PREPARED_NAMED) {
            return $this->namedParams->toArray();
        }
    }

    public function getSql()
    {
        $this->init();
        return $this->sql;
    }

    public function __toString()
    {
        return $this->getSql();
    }
}
