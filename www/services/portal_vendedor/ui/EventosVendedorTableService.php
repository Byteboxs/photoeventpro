<?php

namespace app\services\portal_vendedor\ui;

use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableHeader;

class EventosVendedorTableService
{
    private BootstrapTable $table;
    private array $data = [];
    private $tableId = 'vendedoresTable';
    private EventosVendedorStrategy $strategy;
    private array $columnNames = [];

    public function __construct(EventosVendedorStrategy $strategy, $data, array $columnNames)
    {
        $this->strategy = $strategy;
        $this->columnNames = $columnNames;
        $this->data = $data;
        $this->strategy->apply($this->data);
        // echo '<pre>';
        // var_dump($this->data);
        // echo '</pre>';
        $this->table = $this->init();
    }
    private function init()
    {
        $header = new TableHeader();
        $header->addAllColumns($this->columnNames);
        return UIFactory::createTable($this->data, $header, $this->tableId);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
