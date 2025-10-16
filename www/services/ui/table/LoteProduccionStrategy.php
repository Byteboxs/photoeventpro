<?php

namespace app\services\ui\table;

class LoteProduccionStrategy
{
    public function addColumn(array $item, $idName = 'id'): string
    {
        $id = $item[$idName];

        // $nombreCheckbox = 'chx_' . uniqid();
        // $formBuilder = new FormBuilder();
        // $form = $formBuilder->addCheckbox($id, $nombreCheckbox, '')
        //     ->setValue($nombreCheckbox, $id)
        //     ->build();
        // $renderer = new Bootstrap5FormRenderer();

        // return $renderer->renderElement($form->getElement($nombreCheckbox));

        $data = '
        <div class="btn-group">
            <a class="btn btn-outline-secondary" href="' . APP_DIRECTORY_PATH . '/loteProducto/' . $id . '" data-bs-toggle="tooltip" data-bs-placement="left"
                      data-bs-custom-class="custom-tooltip"
                      data-bs-title="Crear lote de producto">
                <i class="fas fa-vial"></i>
            </a>
        </div>';
        return $data;
    }
}
