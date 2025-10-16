<?php

namespace app\services\strategies;

use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;

class ProyectosDropDownControlStrategy
{
    public function add($item, $args)
    {

        $id = $item[$args['idName']];
        $registrarClienteLink = $args['registrarClienteLink'] . $id;
        $estado = $item[$args['estado']];
        $dropdown = new Dropdown();
        if ($estado == 'programado') {
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-link-external', 'Ver'));
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-edit-alt', 'Editar'));
            $dropdown->addItem(new LinkDropdownItem($registrarClienteLink, 'fas fa-user-plus', 'Registrar Cliente'));
        } else if ($estado == 'activo') {
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-link-external', 'Ver'));
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-edit-alt', 'Editar'));
            $dropdown->addItem(new LinkDropdownItem($registrarClienteLink, 'fas fa-user-plus', 'Registrar Cliente'));
        } else if ($estado == 'finalizado') {
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-link-external', 'Ver'));
        } else if ($estado == 'cancelado') {
            $dropdown->addItem(new LinkDropdownItem('javascript:void(0);', 'bx bx-link-external', 'Ver'));
        }



        return $dropdown->render();
    }
}
