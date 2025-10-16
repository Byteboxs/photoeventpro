<?php

namespace app\services\ui\menu;

class UIDefaultMenu
{
    public static function build(): Menu
    {
        // Ejemplo de JSON de menú
        $menuJson = json_encode([
            'items' => [
                [
                    'label' => 'Dropdown Menu',
                    'icon' => 'bx bx-home-smile',
                    'subItems' => [
                        [
                            'label' => 'Submenu',
                            'active' => true
                        ],
                        [
                            'label' => 'Submenu 2'
                        ],
                        [
                            'label' => 'Dropdown Menu',
                            'icon' => 'bx bx-home-smile',
                            'subItems' => [
                                [
                                    'label' => 'Submenu',
                                    'active' => true
                                ],
                                [
                                    'label' => 'Submenu 2'
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'separator' => 'Apps & Pages'
                ],
                [
                    'label' => 'Email',
                    'icon' => 'bx bx-envelope',
                    'link' => 'javascript:void(0);'
                ],

            ]
        ]);

        // Cargar menú desde string JSON
        return MenuJsonLoader::loadFromString($menuJson);
    }
}
