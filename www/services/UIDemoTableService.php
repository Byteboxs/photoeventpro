<?php

namespace app\services;

use app\helpers\ArrayModifierHelper;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;

class UIDemoTableService
{
    private static $instance = null;
    private $table;
    private $id;
    private $data;
    private function __construct($data, $id)
    {
        $this->data = $data;
        $this->id = $id;
        $this->table = $this->init();
    }

    public static function create($data, $id = 'table')
    {
        if (is_null(self::$instance)) {
            self::$instance = new UIDemoTableService($data, $id);
        }
        return self::$instance;
    }

    public function get(): BootstrapTable
    {
        $this->table = $this->init();
        return $this->table;
    }

    private function init()
    {
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'),
            new TableColumn('Evento'),
            new TableColumn('Inicio'),
            new TableColumn('Fin'),
            new TableColumn('Estado'),
            new TableColumn('Usuarios', '10%'),
            new TableColumn('Organización'),
            new TableColumn('Ubicación'),
            new TableColumn('Dirección'),
            new TableColumn('Opciones', '10%'),
        ]), $this->id);
    }
}
