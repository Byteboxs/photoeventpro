<?php

namespace app\services\ui\menu;

class UICustomerMenu
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
                // [
                //     'separator' => 'Apps & Pages'
                // ],
                // [
                //     'label' => 'FotoApp',
                //     'icon' => 'bx bx-camera',
                //     'link' => APP_DIRECTORY_PATH . '/crear-foto-album'
                // ],
                [
                    'label' => 'Home',
                    'icon' => 'bx bx-home-smile',
                    'link' => APP_DIRECTORY_PATH . '/dashboard-cliente'
                ],
                // [
                //     'label' => 'Servicios',
                //     'icon' => 'bx bxs-store',
                //     'link' => APP_DIRECTORY_PATH . '/servicios'
                // ],

            ]
        ]);

        // Cargar menú desde string JSON
        return MenuJsonLoader::loadFromString($menuJson);
    }
}
