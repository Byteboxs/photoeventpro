<?php

namespace app\services\strategies;

use app\helpers\RouteHelper;
use app\services\ui\badge\Badge;
use app\services\ui\badge\BadgeBuilderService;
use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;

class CustomersEventStrategy
{
    private RouteHelper $routeHelper;

    public function __construct()
    {
        $this->routeHelper = RouteHelper::make();
    }
    public function modifyEstado($item, $args)
    {
        // $estados = [
        //     'activo' => [BadgeBuilderService::COLOR_SUCCESS, BadgeBuilderService::TYPE_DEFAULT, 'Activo'],
        //     'inactivo' => [BadgeBuilderService::COLOR_DARK, BadgeBuilderService::TYPE_DEFAULT, 'inactivo'],
        // ];

        // $estado = $item[$args['estado']];
        // $data = $estados[$estado] ?? $estados['default'];

        // $badge = BadgeBuilderService::make($data[2], $data[0], $data[1]);
        // return $badge->render();

        $estado = $item[$args['estado']];
        if ($estado == 'activo') {
            return new Badge($estado, 'success');
        } else {
            return new Badge($estado, 'danger');
        }
    }
    public function modifyDebe($item, $args)
    {
        $debe = $item['servicios_sin_pagar'];
        $pagados = $item['servicios_pagados'];

        if ($debe == 0 && $pagados == 0) {
            return new Badge('Sin servicios', 'secondary');
        }
        if ($debe == 0 && $pagados > 0) {
            return new Badge('0', 'success');
        }
    }
    public function modifyPagados($item, $args)
    {
        if (isset($args['rawItem'])) {
            $rawItem = $args['rawItem'];
            $debe = $rawItem['servicios_sin_pagar'];
            $pagados = $rawItem['servicios_pagados'];
            if ($debe == 0 && $pagados == 0) {
                return new Badge('Sin servicios', 'secondary');
            }
            if ($debe == 0 && $pagados > 0) {
                return new Badge($pagados, 'success');
            }
            if ($debe != 0 && $pagados == 0) {
                return new Badge($pagados, 'danger');
            }
            if ($debe != 0 && $pagados > 0) {
                return new Badge($pagados, 'warning');
            }
        }
    }
    public function addControls($item, $args)
    {
        $id = $item[$args['idName']];

        $dropdown = new Dropdown();
        $dropdown->addItem(new LinkDropdownItem(
            str_replace("{idCliente}", $id, $args['eventoClienteDetalleView']),
            'bx bx-link-external',
            'Ver'
        ));
        $dropdown->addItem(new LinkDropdownItem(
            str_replace("{id}", $id, $args['linkCargarImagenes']),
            'fas fa-file-upload',
            'Cargar imagenes'
        ));
        // $dropdown->addItem(new LinkDropdownItem(
        //     str_replace("{idCliente}", $id, $args['eventosPagoClienteEfectivoView']),
        //     'fas fa-cash-register',
        //     'Registrar pago efectivo'
        // ));
        return $dropdown->render();
    }

    public function addAvatar($item, $args)
    {
        $idEvento = $args['idEvento'];

        $idCliente = $item['customer_id'];
        $url = $this->routeHelper->search('eventoClienteDetalleView')->getUrl(['idEvento' => $idEvento, 'idCliente' => $idCliente]);
        $primer_nombre = $item['primer_nombre'];
        $primer_apellido = $item['primer_apellido'];

        $inicialNombre = strtoupper(substr($primer_nombre, 0, 1));
        $inicialApellido = strtoupper(substr($primer_apellido, 0, 1));

        $inicial = $inicialNombre . $inicialApellido;

        $avatar = '<div class="d-flex justify-content-start align-items-center user-name">
        <div class="avatar-wrapper">
            <div class="avatar avatar-sm me-4">
            <a href="' . $url . '">
                <span class="avatar-initial rounded-circle bg-label-success">%s</span>
            </a>
            
            </div>
        </div>
        <a href="' . $url . '" style="color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)) );">
        <div class="d-flex flex-column">
            <span class="fw-large">%s</span>
            <small>%s</small>
            </div>
        </div>
        </a>
        ';
        return sprintf($avatar, $inicial, $item['nombre_completo'], $item['email']);
    }
}
