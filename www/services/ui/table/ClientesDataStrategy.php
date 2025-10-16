<?php

namespace app\services\ui\table;

class ClientesDataStrategy
{
    public function addControls(array $item)
    {
        $id = $item['cliente_id'];

        $data = '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-2" role="group" aria-label="First group">';
        $data = '
            <a class="btn btn-outline-secondary" href="' . APP_DIRECTORY_PATH . '/editar-cliente/' . $id . '" data-bs-toggle="tooltip" data-bs-placement="left"
                  data-bs-custom-class="custom-tooltip"
                  data-bs-title="Editar cliente">
                  <i class="fas fa-user-edit"></i>
            </a>';

        $data .= '</div>
                </div>';
        return $data;
    }
}
