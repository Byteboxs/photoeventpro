<?php

namespace app\services\ui\menu;

use app\helpers\RouteHelper;

class UIVendedorMenu
{
    public static function build(): Menu
    {
        // Ejemplo de JSON de menú
        $menuJson = json_encode([
            'items' => [
                [
                    'label' => 'Home',
                    'icon' => 'bx bx-home-smile',
                    'link' => RouteHelper::getUrlFor('salesPersonDashboardView'),
                ],
                [
                    'label' => 'Eventos',
                    'icon' => 'bx bx-clipboard',
                    'link' => RouteHelper::getUrlFor('seleccionarEventoView'),
                ],

            ]
        ]);

        // Cargar menú desde string JSON
        return MenuJsonLoader::loadFromString($menuJson);
    }
}
