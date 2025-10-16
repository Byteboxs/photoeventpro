<?php

namespace app\services;

use app\core\Singleton;
use app\services\ui\form\FormBuilder;

class UIFormsProductoService extends Singleton
{
    private $formBuilder;
    protected function __construct()
    {
        parent::__construct();
        $this->formBuilder = new FormBuilder();
    }

    public function get(array $args = [])
    {
        if (isset($args['activarProducto']) && $args['activarProducto'] === true) {
            $this->formBuilder->addSwitchCheckbox('status', 'status', 'Estado del producto');
            $this->formBuilder->setValue('status', $args['producto']->status);
            $this->formBuilder->setAttribute('status', 'required', true);
            if ($args['producto']->status === "activo") {
                $this->formBuilder->setAttribute('status', 'checked', true);
            }
        }

        $this->formBuilder->addSelect('categoria_id', 'categoria_id', 'Categoria del producto*', $args['categorias']);
        $this->formBuilder->setAttribute('categoria_id', 'required', true);
        if (isset($args['producto']->categoria_id)) {
            $this->formBuilder->setValue('categoria_id', $args['producto']->categoria_id);
        }

        if (isset($args['producto']->id)) {
            $this->formBuilder->addInput('id', 'id', 'hidden');
            $this->formBuilder->setValue('id', $args['producto']->id);
        }

        $this->formBuilder->addInput('nombre', 'nombre', 'text', 'Producto*');
        $this->formBuilder->setPlaceholder('nombre', 'Ingrese el nombre del producto');
        $this->formBuilder->setAttribute('nombre', 'required', true);
        if (isset($args['producto']->id)) {
            $this->formBuilder->setValue('nombre', $args['producto']->nombre);
        }

        $this->formBuilder->addTextarea('descripcion', 'descripcion', 'Descripción del producto*');
        $this->formBuilder->setPlaceholder('descripcion', 'Ingrese la descripción del producto');
        $this->formBuilder->setAttribute('descripcion', 'required', true);
        if (isset($args['producto']->descripcion)) {
            $this->formBuilder->setValue('descripcion', $args['producto']->descripcion);
        }

        $this->formBuilder->addInput('precio', 'precio', 'number', 'Precio del producto*');
        $this->formBuilder->setPlaceholder('precio', 'Ingrese el precio del producto');
        $this->formBuilder->setAttribute('precio', 'required', true);
        if (isset($args['producto']->precio)) {
            $this->formBuilder->setValue('precio', $args['producto']->precio);
        }

        $this->formBuilder->addInput('max_fotos', 'max_fotos', 'number', 'Máximo de fotos*');
        $this->formBuilder->setPlaceholder('max_fotos', 'Ingrese el máximo de fotos a entregar');
        $this->formBuilder->setAttribute('max_fotos', 'required', true);
        $this->formBuilder->setAttribute('max_fotos', 'min', '1');

        if (isset($args['producto']->max_fotos)) {
            $this->formBuilder->setValue('max_fotos', $args['producto']->max_fotos);
        }

        return $this->formBuilder->build();
    }
}
