<?php

namespace app\services;

use app\core\Singleton;
use app\services\ui\menu\UIAdministratorMenu;
use app\services\ui\menu\UICustomerMenu;
use app\services\ui\menu\UIDefaultMenu;
use app\services\ui\menu\UIVendedorMenu;

class UIMenuService extends Singleton
{
    public function build()
    {
        return match ($this->args[0]) {
            'Administrador' => UIAdministratorMenu::build(),
            'Vendedor' => UIVendedorMenu::build(),
            'Cliente' => UICustomerMenu::build(),
            default => UIDefaultMenu::build(),
        };
    }
}
