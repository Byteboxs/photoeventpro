<?php

namespace app\services;

use app\core\IFormView;
use app\core\Singleton;
use app\services\ui\form\Form;
use app\services\ui\form\FormBuilder;

class UIFormsRegisterCustomerService extends Singleton implements IFormView
{
    protected function __construct()
    {
        parent::__construct();
    }

    public function getForm(array $args = []): Form
    {
        $formBuilder = new FormBuilder();

        return $formBuilder->addInput('project_id', 'project_id', 'hidden', '')
            ->setValue('project_id', $args['project_id'])
            ->addInput('primer_nombre', 'primer_nombre', 'text', 'Primer Nombre*')
            ->setPlaceholder('primer_nombre', 'Ingrese el primer nombre')
            ->setAttribute('primer_nombre', 'required', true)

            ->addInput('segundo_nombre', 'segundo_nombre', 'text', 'Segundo Nombre')
            ->setPlaceholder('segundo_nombre', 'Ingrese el segundo nombre')

            ->addInput('primer_apellido', 'primer_apellido', 'text', 'Primer Apellido*')
            ->setPlaceholder('primer_apellido', 'Ingrese el primer apellido')
            ->setAttribute('primer_apellido', 'required', true)

            ->addInput('segundo_apellido', 'segundo_apellido', 'text', 'Segundo Apellido')
            ->setPlaceholder('segundo_apellido', 'Ingrese el segundo apellido')

            ->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Ingrese el email')
            ->setAttribute('email', 'required', true)

            ->addInput('direccion', 'direccion', 'text', 'Dirección de residencia')
            ->setPlaceholder('direccion', 'Ingrese la dirección')
            ->setAttribute('direccion', 'required', true)

            ->addInput('telefono', 'telefono', 'tel', 'Teléfono')
            ->setPlaceholder('telefono', 'Ingrese el teléfono')
            ->setAttribute('telefono', 'required', true)

            ->addSelect('document_type_id', 'document_type_id', 'Tipo de Documento*', $args['tiposDocumento'])
            ->setPlaceholder('document_type_id', 'Seleccione el tipo de documento')
            ->setAttribute('document_type_id', 'required', true)

            ->addInput('numero_identificacion', 'numero_identificacion', 'number', 'Numero de documento*')
            ->setPlaceholder('numero_identificacion', 'Ingrese número de documento')
            ->setAttribute('numero_identificacion', 'required', true)
            ->setAttribute('numero_identificacion', 'minlength', 7)


            ->addInput('password', 'password', 'password', 'Contraseña*')
            ->setPlaceholder('password', 'Ingrese la contraseña')
            ->setAttribute('password', 'required', true)
            ->addInput('confirm_password', 'confirm_password', 'password', 'Confirmar Contraseña*')
            ->setPlaceholder('confirm_password', 'Confirme la contraseña')
            ->setAttribute('confirm_password', 'required', true)

            ->addInput('nombre_contacto', 'nombre_contacto', 'text', 'Nombre de contacto')
            ->setPlaceholder('nombre_contacto', 'Ingrese el nombre de contacto')

            ->addInput('direccion_envio', 'direccion_envio', 'text', 'Dirección de envio*')
            ->setPlaceholder('direccion_envio', 'Ingrese la dirección de envio')
            // ->setAttribute('direccion_envio', 'required', true)

            ->addInput('telefono_contacto', 'telefono_contacto', 'tel', 'Teléfono de contacto*')
            ->setPlaceholder('telefono_contacto', 'Ingrese el teléfono de contacto')
            // ->setAttribute('telefono_contacto', 'required', true)

            ->build();
    }
}
