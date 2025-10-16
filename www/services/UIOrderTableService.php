<?php

namespace app\services;

use app\helpers\ArrayModifierHelper;
use app\services\ui\builder\UIFactory;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\TableColumn;
use app\services\ui\table\TableHeader;
use app\services\strategies\OrdersStrategy;

class UIOrderTableService
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
            'imagen_servicio',
            $this->strategy,
            $this->data,
            ['col' => 'imagen_servicio'],
            'modifyImageOrders'
        );

        // ArrayModifierHelper::modifyColumn(
        //     'estado_pago_servicio',
        //     $this->strategy,
        //     $this->data,
        //     [],
        //     'modifyEstadoPagoServicio'
        // );

        ArrayModifierHelper::modifyColumn(
            'estado_seleccion_fotos',
            $this->strategy,
            $this->data,
            [],
            'modifyEstadoSeleccionFotos'
        );

        ArrayModifierHelper::modifyColumn(
            'status_entrega',
            $this->strategy,
            $this->data,
            ['col' => 'status_entrega'],
            'modifyEstatusEntrega'
        );
        ArrayModifierHelper::modifyColumn(
            'nombre_completo_usuario',
            $this->strategy,
            $this->data,
            ['col' => 'nombre_completo_usuario'],
            'modifyNombreCompletoUsuario'
        );
        ArrayModifierHelper::modifyColumn(
            'nombre_evento_proyecto',
            $this->strategy,
            $this->data,
            ['col' => 'nombre_evento_proyecto'],
            'modifyNombreEventoProyecto'
        );
        ArrayModifierHelper::modifyColumn(
            'nombre_categoria',
            $this->strategy,
            $this->data,
            ['col' => 'nombre_categoria'],
            'modifyNombreCategoria'
        );
        ArrayModifierHelper::modifyColumn(
            'estado_pago_compra',
            $this->strategy,
            $this->data,
            ['col' => 'estado_pago_compra'],
            'modifyEstadoPagoCompra'
        );

        ArrayModifierHelper::removeColumn('nombre_servicio', $this->data);
        ArrayModifierHelper::removeColumn('primer_nombre_usuario', $this->data);
        ArrayModifierHelper::removeColumn('segundo_nombre_usuario', $this->data);
        ArrayModifierHelper::removeColumn('primer_apellido_usuario', $this->data);
        ArrayModifierHelper::removeColumn('segundo_apellido_usuario', $this->data);
        ArrayModifierHelper::removeColumn('email_usuario', $this->data);
        ArrayModifierHelper::removeColumn('user_id', $this->data);
        ArrayModifierHelper::removeColumn('customer_id', $this->data);
        ArrayModifierHelper::removeColumn('project_id', $this->data);
        ArrayModifierHelper::removeColumn('service_id', $this->data);
        ArrayModifierHelper::removeColumn('customer_event_id', $this->data);
        ArrayModifierHelper::removeColumn('purchase_order_id', $this->data);
        ArrayModifierHelper::removeColumn('max_fotos_servicio', $this->data);
        ArrayModifierHelper::removeColumn('total_bruto_compra', $this->data);
        ArrayModifierHelper::removeColumn('direccion_usuario', $this->data);
        ArrayModifierHelper::removeColumn('telefono_usuario', $this->data);
        ArrayModifierHelper::removeColumn('numero_identificacion_usuario', $this->data);
        ArrayModifierHelper::removeColumn('nombre_tipo_documento', $this->data);
        ArrayModifierHelper::removeColumn('codigo_tipo_documento', $this->data);
        ArrayModifierHelper::removeColumn('nombre_rol', $this->data);
        ArrayModifierHelper::removeColumn('fecha_inicio_proyecto', $this->data);
        ArrayModifierHelper::removeColumn('fecha_fin_proyecto', $this->data);
        ArrayModifierHelper::removeColumn('hora_ceremonia_proyecto', $this->data);
        ArrayModifierHelper::removeColumn('nombre_institucion', $this->data);
        ArrayModifierHelper::removeColumn('nombre_ubicacion', $this->data);
        ArrayModifierHelper::removeColumn('direccion_ubicacion', $this->data);
        ArrayModifierHelper::removeColumn('fecha_creacion_cliente_evento', $this->data);
        ArrayModifierHelper::removeColumn('fecha_actualizacion_cliente_evento', $this->data);
    }
    private function init()
    {
        $this->applyStrategy();
        return UIFactory::createTable($this->data, new TableHeader([
            new TableColumn('id', '5%'), //order_detail_id
            // new TableColumn('purchase_order_id'),
            // new TableColumn('user_id'),
            // new TableColumn('customer_id'),
            // new TableColumn('project_id'),
            // new TableColumn('service_id'),
            // new TableColumn('customer_event_id'),
            // new TableColumn('nombre_servicio'),
            // new TableColumn('max_fotos_servicio'),
            new TableColumn('servicio'), // imagen_servicio
            new TableColumn('categoria'), // nombre_categoria
            new TableColumn('fecha orden compra'), //fecha_orden_compra
            // new TableColumn('total_bruto_compra'),
            new TableColumn('total'), // total_neto_compra
            new TableColumn('Metodo pago'), // metodo_pago_compra
            // new TableColumn('email_usuario'),
            // new TableColumn('primer_nombre_usuario'),
            // new TableColumn('segundo_nombre_usuario'),
            // new TableColumn('primer_apellido_usuario'),
            // new TableColumn('segundo_apellido_usuario'),
            new TableColumn('cliente'), // nombre_completo_usuario
            // new TableColumn('direccion_usuario'),
            // new TableColumn('telefono_usuario'),
            // new TableColumn('numero_identificacion_usuario'),
            // new TableColumn('nombre_tipo_documento'),
            // new TableColumn('codigo_tipo_documento'),
            // new TableColumn('nombre_rol'),
            new TableColumn('Evento'), // nombre_evento_proyecto
            // new TableColumn('fecha_inicio_proyecto'),
            // new TableColumn('fecha_fin_proyecto'),
            // new TableColumn('hora_ceremonia_proyecto'),
            // new TableColumn('nombre_institucion'),
            // new TableColumn('nombre_ubicacion'),
            // new TableColumn('direccion_ubicacion'),
            // new TableColumn('fecha_creacion_cliente_evento'),
            // new TableColumn('fecha_actualizacion_cliente_evento'),
            new TableColumn('estado proyecto'), // estado_proyecto
            new TableColumn('estado cliente'), // estado_cliente_evento
            // new TableColumn('estado compra'), // estado_pago_compra
            new TableColumn('Entrega Pedido'), // status_entrega
            new TableColumn('Seleccionó Fotos'), // estado_seleccion_fotos
            // new TableColumn('numero_pedidos_pendientes_impresion'), // estado_seleccion_fotos
        ]), $this->tableId);
    }
    public function get(): BootstrapTable
    {
        return $this->table;
    }
}
