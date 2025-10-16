<?php

namespace app\services\ui\menu;

use app\helpers\RouteHelper;

class UIAdministratorMenu
{
    public static function build(): Menu
    {
        // Ejemplo de JSON de menú
        $menuJson = json_encode([
            'items' => [
                // [
                //     'label' => 'Dropdown Menu',
                //     'icon' => 'bx bx-home-smile',
                //     'subItems' => [
                //         [
                //             'label' => 'Submenu',
                //             'active' => true
                //         ],
                //         [
                //             'label' => 'Submenu 2'
                //         ],
                //         [
                //             'label' => 'Dropdown Menu',
                //             'icon' => 'bx bx-home-smile',
                //             'subItems' => [
                //                 [
                //                     'label' => 'Submenu',
                //                     'active' => true
                //                 ],
                //                 [
                //                     'label' => 'Submenu 2'
                //                 ]
                //             ]
                //         ]
                //     ]
                // ],
                [
                    'label' => 'Home',
                    'icon' => 'bx bx-home-smile',
                    'link' => APP_DIRECTORY_PATH . '/dashboard-administrador'
                ],
                [
                    'label' => 'Gestión de eventos',
                    'icon' => 'bx bx-clipboard',
                    'link' => RouteHelper::getUrlFor('eventosView')
                    // 'subItems' => [
                    //     [
                    //         'label' => 'Eventos',
                    //         'icon' => 'bx bx-clipboard',
                    //         'link' => RouteHelper::getUrlFor('eventosView')
                    //     ],
                    //     [
                    //         'label' => 'Crear',
                    //         'icon' => 'bx bx-clipboard',
                    //         'link' => RouteHelper::getUrlFor('eventosView')
                    //     ]

                    // ]
                ],
                [
                    'label' => 'Productos',
                    'icon' => 'fas fa-shopping-bag',
                    'link' => APP_DIRECTORY_PATH . '/listado-de-productos'
                ],
                [
                    'label' => 'Pedidos',
                    'icon' => 'fas fa-cart-plus',
                    'link' => APP_DIRECTORY_PATH . '/orders'
                ],
                [
                    'label' => 'Vendedores',
                    'icon' => 'fas fa-id-card-alt',
                    'link' => APP_DIRECTORY_PATH . '/vendedores'
                ],

            ]
        ]);

        // Cargar menú desde string JSON
        return MenuJsonLoader::loadFromString($menuJson);
    }
}
