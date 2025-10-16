<?php

namespace app\core\database\builder;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Delete extends QueryPart
{
    const PREPARED_POSITIONAL = 1;
    const PREPARED_NAMED = 2;
    const RAW = 3;

    // private $prepared = self::RAW;
    private $table;
    protected $posParams;
    protected $namedParams;

    public function __construct(string $table)
    {
        parent::__construct();
        $this->namedParams = new Map();
        $this->posParams = new ArrayList();
        // $this->prepared = $prepared;
        $this->table = $table;
    }

    public function init()
    {
        $this->sql = '';
        $this->sql .= "DELETE FROM {$this->table} ";
    }

    public function getSql(): string
    {
        $this->init();
        return $this->sql;
    }

    public function __toString()
    {
        return $this->getSql();
    }
}
