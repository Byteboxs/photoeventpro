<?php

namespace app\services;

use app\helpers\ArrayModifierHelper;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;
use app\services\strategies\OrdersStrategy;

class UIOrderDetailsTableService
{
    private BootstrapTable $table;
    private array $data = [];
    private $tableId = 'orderDetailsTable';
    private OrdersStrategy $strategy;

    public function __construct($data)
    {
        $this->strategy = new OrdersStrategy();
        $this->data = $data;
        $this->table = $this->init();
    }
    private function applyStrategy()
    {
        // ArrayModifierHelper::addColumn('opciones', $this->strategy, $this->data, [
        //     'idName' => $this->idKey,
        //     'eventoClienteDetalleView' => $this->extraArgs['eventoClienteDetalleView'],
        //     'linkCargarImagenes' => $this->extraArgs['linkCargarImagenes'],
        //     'eventosPagoClienteEfectivoView' => $this->extraArgs['eventosPagoClienteEfectivoView'],
        // ], 'addControls', 'numero_identificacion');

        ArrayModifierHelper::modifyColumn(
            'image',
            $this->strategy,
            $this->data,
            [],
            'modifyImage'
        );

        ArrayModifierHelper::modifyColumn(
            'estado_pago_servicio',
            $this->strategy,
            $this->data,
            [],
            'modifyEstadoPagoServicio'
        );

        ArrayModifierHelper::modifyColumn(
            'estado_seleccion_fotos',
            $this->strategy,
            $this->data,
            [],
            'modifyEstadoSeleccionFotos'
        );

        ArrayModifierHelper::removeColumn('descripcion_servicio', $this->data);
        ArrayModifierHelper::removeColumn('purchase_order_id', $this->data);
        ArrayModifierHelper::removeColumn('service_id', $this->data);
        ArrayModifierHelper::removeColumn('cantidad', $this->data);
        ArrayModifierHelper::removeColumn('precio_unitario', $this->data);
        ArrayModifierHelper::removeColumn('nombre_servicio', $this->data);
    }
    private function init()
    {
        $this->applyStrategy();
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'),
            new TableColumn(''),
            new TableColumn('categoria'),
            // new TableColumn('servicio'),
            // new TableColumn('descripcion_servicio'),
            new TableColumn('precio'),
            new TableColumn('estado'),
            new TableColumn('imágenes'),
            // new TableColumn('purchase_order_id'),
            // new TableColumn('service_id'),
            // new TableColumn('cantidad'),
            // new TableColumn('precio_unitario'),
            // new TableColumn('subtotal'),
            // new TableColumn('Servicios'), // servicios_seleccionados
            // new TableColumn('Debe'), // servicios_sin_pagar
            // new TableColumn('Pagó'), // servicios_pagados
            // new TableColumn('Teléfono'),
            // new TableColumn('Tipo Documento'),
            // new TableColumn('Número Documento'),
            // new TableColumn('', '10%'),
            // new TableColumn('', '10%'),
        ]), $this->tableId);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
