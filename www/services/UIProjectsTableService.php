<?php

namespace app\services;

use app\core\Singleton;
use app\helpers\ArrayModifierHelper;
use app\services\strategies\ProjectsStrategy;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;

class UIProjectsTableService extends Singleton
{
    private $table;
    private $tableId;
    private $idKey;
    private $data;
    private ProjectsStrategy $strategy;
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
        ArrayModifierHelper::removeColumn('ubicacion', $this->data);
        ArrayModifierHelper::removeColumn('direccion', $this->data);
        ArrayModifierHelper::removeColumn('fin', $this->data);
        ArrayModifierHelper::removeColumn('pricing_plans_id', $this->data);

        ArrayModifierHelper::addColumn('opciones', $this->strategy, $this->data, [
            'idName' => $this->idKey,
            'estado' => 'estado',
            'linkRegistrarCliente' => $this->extraArgs['linkRegistrarCliente'],
            'linkEditarProyecto' => $this->extraArgs['linkEditarProyecto'],
            'linkVerProyecto' => $this->extraArgs['linkVerProyecto'],
            'linkPosEfectivo' => $this->extraArgs['linkPosEfectivo'],


        ], 'addControls');

        ArrayModifierHelper::modifyColumn('estado', $this->strategy, $this->data, [
            'estado' => 'estado'
        ], 'modifyEstado');

        ArrayModifierHelper::modifyColumn('hora_ceremonia', $this->strategy, $this->data, ['column' => 'hora_ceremonia'], 'modifyHora');
        ArrayModifierHelper::modifyColumn('nombre', $this->strategy, $this->data, [
            'column' => 'nombre',
            'idName' => $this->idKey,
            'linkDetalleProyecto' => $this->extraArgs['linkDetalleProyecto']
        ], 'modifyNombre');
    }
    private function init()
    {
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'),
            new TableColumn('Evento'),
            new TableColumn('Inicio'),
            // new TableColumn('Fin'),
            new TableColumn('Hora'),
            new TableColumn('Estado'),
            // new TableColumn('Usuarios', '10%'),
            new TableColumn('Organización'),
            // new TableColumn('Ubicación'),
            // new TableColumn('Dirección'),
            new TableColumn('', '10%'),
        ]), $this->idKey);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
