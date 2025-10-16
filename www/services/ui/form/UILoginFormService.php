<?php

namespace app\services\ui\form;

use app\core\Singleton;

class UILoginFormService extends Singleton
{
    private $formBuilder;
    private function init()
    {
        return $this->formBuilder->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Email')
            ->setAttribute('email', 'required', true)
            ->setValue('email', 'administrador@example.com')
            ->addInput('password', 'password', 'password', 'Contraseña*')
            ->setPlaceholder('password', 'Contraseña')
            ->setAttribute('password', 'required', true)
            ->build();
    }

    public function getForm()
    {
        $this->formBuilder = new FormBuilder();
        return $this->init();
    }
}
