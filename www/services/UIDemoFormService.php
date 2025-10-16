<?php

namespace app\services;

use app\services\ui\form\FormBuilder;

class UIDemoFormService
{
    private static $instance = null;
    private $formBuilder;
    private $form;
    private $data;
    private function __construct($data)
    {
        $this->formBuilder = new FormBuilder();
        $this->data = $data;
        $this->form = $this->init();
    }
    public static function create($data)
    {
        if (is_null(self::$instance)) {
            self::$instance = new UIDemoFormService($data);
        }
        return self::$instance;
    }

    public function getForm()
    {
        return $this->form;
    }

    private function init()
    {
        return $this->formBuilder->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Email')
            ->setAttribute('email', 'required', true)
            ->addInput('nombres', 'nombres', 'text', 'Nombres*')
            ->setPlaceholder('nombres', 'Nombres')
            ->setAttribute('nombres', 'required', true)
            ->addInput('apellidos', 'apellidos', 'text', 'Apellidos*')
            ->setPlaceholder('apellidos', 'Apellidos')
            ->setAttribute('apellidos', 'required', true)
            ->addSelect('tipo_documento_id', 'tipo_documento_id', 'Tipo de Documento*', $this->data)
            ->setAttribute('tipo_documento_id', 'required', true)
            ->addInput('numero_identifiacion', 'numero_identifiacion', 'number', 'NIT*')
            ->setPlaceholder('numero_identifiacion', 'nit')
            ->setAttribute('numero_identifiacion', 'required', true)
            ->addInput('razon_social', 'razon_social', 'text', 'Razon social*')
            ->setPlaceholder('razon_social', 'Razon social')
            ->setAttribute('razon_social', 'required', true)
            ->addInput('direccion_facturacion', 'direccion_facturacion', 'text', 'Dirección de facturación*')
            ->setPlaceholder('direccion_facturacion', 'Dirección de facturación')
            ->setAttribute('direccion_facturacion', 'required', true)
            ->addInput('nacimiento', 'nacimiento', 'date', 'Fecha de nacimiento*')
            ->setPlaceholder('nacimiento', 'Fecha de nacimiento')
            ->setAttribute('nacimiento', 'required', true)

            ->build();
    }
}
