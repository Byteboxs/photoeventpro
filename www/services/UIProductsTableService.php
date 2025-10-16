<?php

namespace app\services;

use app\core\Singleton;
use app\helpers\ArrayModifierHelper;
use app\services\strategies\ProductsStrategy;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;

class UIProductsTableService extends Singleton
{
    private $table;
    private $tableId;
    private $idKey;
    private $data;
    private ProductsStrategy $strategy;
    private $extraArgs;
    protected function __construct($data, $strategy, $idKey, $tableId, $extraArgs = [])
    {
        parent::__construct($data, $strategy, $idKey, $tableId, $extraArgs);
        [$this->data, $this->strategy, $this->idKey, $this->tableId, $this->extraArgs] = $this->args;
        $this->applyStrategy();
        $this->table = $this->init();
    }
    private function applyStrategy()
    {
        ArrayModifierHelper::removeColumn('descripcion', $this->data);
        ArrayModifierHelper::addColumn('opciones', $this->strategy, $this->data, [
            'idName' => $this->idKey,
            'column' => 'estado',
            'linkVer' => $this->extraArgs['linkVer'],
        ], 'addControls');
        ArrayModifierHelper::modifyColumn('precio', $this->strategy, $this->data, [
            'column' => 'precio'
        ], 'formatPrecio');
        ArrayModifierHelper::modifyColumn('estado', $this->strategy, $this->data, [
            'column' => 'estado'
        ], 'modifyEstado');
    }
    private function init()
    {
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'),
            new TableColumn('Categoria'),
            new TableColumn('Nombre'),
            new TableColumn('Precio'),
            new TableColumn('Max Fotos', '10%'),
            new TableColumn('Min Fotos', '10%'),
            new TableColumn('Estado'),
            new TableColumn('', '10%'),
        ]), $this->idKey);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
