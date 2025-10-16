<?php

namespace app\services\ui\table;

use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\ui\form\FormBuilder;

class DonacionesStrategy
{
    public function addColumn(array $item, $idName = 'id'): string
    {
        $id = $item[$idName];

        $nombreCheckbox = 'chx_' . uniqid();
        $formBuilder = new FormBuilder();
        $form = $formBuilder->addCheckbox($id, $nombreCheckbox, '')
            ->setValue($nombreCheckbox, $id)
            ->build();
        $renderer = new Bootstrap5FormRenderer();

        return $renderer->renderElement($form->getElement($nombreCheckbox));
    }
}
