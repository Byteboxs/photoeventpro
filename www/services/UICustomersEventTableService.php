<?php

namespace app\services;

use app\core\Singleton;
use app\helpers\ArrayModifierHelper;
use app\services\strategies\CustomersEventStrategy;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;

class UICustomersEventTableService extends Singleton
{
    private BootstrapTable $table;
    private $tableId;
    private $idKey;
    private $data;
    private CustomersEventStrategy $strategy;
    private $extraArgs;
    private $rawData;
    protected function __construct($data, $strategy, $idKey, $tableId, $extraArgs = [])
    {
        parent::__construct($data, $strategy, $idKey, $tableId, $extraArgs);
        [$this->data, $this->strategy, $this->idKey, $this->tableId, $this->extraArgs] = $this->args;
        $this->rawData = $data;
        $this->applyStrategy();
        $this->table = $this->init();
    }
    private function applyStrategy()
    {
        ArrayModifierHelper::addColumn('opciones', $this->strategy, $this->data, [
            'idName' => $this->idKey,
            'eventoClienteDetalleView' => $this->extraArgs['eventoClienteDetalleView'],
            'linkCargarImagenes' => $this->extraArgs['linkCargarImagenes'],
            'eventosPagoClienteEfectivoView' => $this->extraArgs['eventosPagoClienteEfectivoView'],
        ], 'addControls', 'numero_identificacion');

        ArrayModifierHelper::modifyColumn('estado', $this->strategy, $this->data, [
            'estado' => 'estado'
        ], 'modifyEstado');

        ArrayModifierHelper::modifyColumn('servicios_sin_pagar', $this->strategy, $this->data, [], 'modifyDebe');

        ArrayModifierHelper::modifyColumn('servicios_pagados', $this->strategy, $this->data, ['rawArray' => $this->rawData], 'modifyPagados');

        ArrayModifierHelper::addColumn('Usuario', $this->strategy, $this->data, [
            'idEvento' => $this->extraArgs['idEvento'],
        ], 'addAvatar', 'customer_id');

        ArrayModifierHelper::removeColumn('email', $this->data);
        ArrayModifierHelper::removeColumn('primer_nombre', $this->data);
        ArrayModifierHelper::removeColumn('segundo_nombre', $this->data);
        ArrayModifierHelper::removeColumn('primer_apellido', $this->data);
        ArrayModifierHelper::removeColumn('segundo_apellido', $this->data);
        ArrayModifierHelper::removeColumn('nombre_completo', $this->data);
        ArrayModifierHelper::removeColumn('direccion', $this->data);
        ArrayModifierHelper::removeColumn('servicios_seleccionados', $this->data);
    }
    private function init()
    {
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'),
            new TableColumn('Cliente'),
            new TableColumn('Estado'),
            // new TableColumn('Servicios'), // servicios_seleccionados
            new TableColumn('Debe'), // servicios_sin_pagar
            new TableColumn('Pagó'), // servicios_pagados
            new TableColumn('Teléfono'),
            new TableColumn('Tipo Documento'),
            new TableColumn('Número Documento'),
            new TableColumn('', '10%'),
            new TableColumn('', '10%'),
        ]), $this->idKey);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
'["customer_id"]=>
      int(2)
      ["email"]=>
      string(28) "villarragajulieta7@gmail.com"
      ["primer_nombre"]=>
      string(7) "Julieta"
      ["segundo_nombre"]=>
      NULL
      ["primer_apellido"]=>
      string(10) "Villarraga"
      ["segundo_apellido"]=>
      string(8) "Corredor"
      ["nombre_completo"]=>
      string(27) "Julieta Villarraga Corredor"
      ["estado"]=>
      string(6) "activo"
      ["servicios_seleccionados"]=>
      int(2)
      ["servicios_sin_pagar"]=>
      string(1) "0"
      ["servicios_pagados"]=>
      string(1) "2"
      ["direccion"]=>
      string(24) "Calle 21 #88A-79 Casa 60"
      ["telefono"]=>
      string(1) "0"
      ["document_type"]=>
      string(20) "Tarjeta de identidad"
      ["numero_identificacion"]=>
      string(10) "1025143204"
      ["notas"]=>
      NULL';
