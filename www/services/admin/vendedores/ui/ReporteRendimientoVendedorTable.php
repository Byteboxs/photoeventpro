<?php

namespace app\services\admin\vendedores\ui;

use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableHeader;

class ReporteRendimientoVendedorTable
{
    private BootstrapTable $table;
    private array $data = [];
    private $tableId = 'vendedoresTable';
    private ReporteRendimientoVendedorStrategy $strategy;
    private array $columnNames = [];

    public function __construct(ReporteRendimientoVendedorStrategy $strategy, $data, array $columnNames)
    {
        $this->strategy = $strategy;
        // $this->strategy = new VendedoresStrategy();
        $this->columnNames = $columnNames;
        $this->data = $data;
        // $this->applyStrategy();
        $this->strategy->apply($this->data);
        $this->table = $this->init();
    }
    private function applyStrategy()
    {
        $this->strategy->apply($this->data);
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
