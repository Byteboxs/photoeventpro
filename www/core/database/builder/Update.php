<?php

namespace app\core\database\builder;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Update extends QueryPart
{
    const PREPARED_POSITIONAL = 1;
    const PREPARED_NAMED = 2;
    const RAW = 3;

    private $prepared = self::RAW;
    private $table;
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
        $set = [];
        if ($this->prepared == self::RAW) {
            foreach ($params as $key => $value) {
                if (is_null($value)) {
                    $set[] = "$key = null";
                } else {
                    $set[] = "$key = '$value'";
                }
            }
        } else if ($this->prepared == self::PREPARED_POSITIONAL) {
            foreach ($params as $key => $value) {
                $set[] = "$key = ?";
                $this->posParams->add($value);
            }
        } else if ($this->prepared == self::PREPARED_NAMED) {
            foreach ($params as $key => $value) {
                $set[] = "$key = :$key" . uniqid();
                $posId = $key . uniqid();
                $this->namedParams->add($posId, $value);
            }
        }
        $sqlSet = implode(', ', $set);

        $this->sql .= "UPDATE {$this->table} SET $sqlSet ";
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
