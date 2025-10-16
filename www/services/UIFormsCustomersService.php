<?php

namespace app\services;

use app\core\Singleton;
use app\services\ui\form\FormBuilder;

class UIFormsCustomersService extends Singleton
{
    protected function __construct()
    {
        parent::__construct();
    }

    public function getSelectCustomersForm(array $args = [])
    {
        $formBuilder = new FormBuilder();
        return $formBuilder->addSelect('customer_id', 'customer_id', 'Cliente*', $args)
            ->setPlaceholder('customer_id', 'Seleccione un cliente')
            ->setAttribute('customer_id', 'required', true)
            ->build();
    }
    public function getHiddenCustomersForm($customer_id)
    {
        // var_dump($customer_id);
        $formBuilder = new FormBuilder();
        return $formBuilder
            ->addInput('customer_id', 'customer_id', 'Hidden', '')
            ->setValue('customer_id', $customer_id)
            ->build();
    }
}
