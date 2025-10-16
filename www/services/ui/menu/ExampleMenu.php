<?php

namespace app\services\ui\menu;

class ExampleMenu
{
    public static function createFullMenu(): Menu
    {
        $builder = new MenuBuilder();

        // Primer menú desplegable: Dashboards
        $dashboardsMenu = MenuItem::create(
            'Dropdown Menu',
            'bx bx-home-smile',
            'javascript:void(0);',
            [
                MenuItem::create('Submenu 1', null, 'javascript:void(0);', [], true),
                MenuItem::create('Submenu 2', null, 'https://www.google.com/')
            ]
        );

        // Segundo menú desplegable: Front Pages
        $frontPagesMenu = MenuItem::create(
            'Dropdown Menu',
            'bx bx-store',
            'javascript:void(0);',
            [
                MenuItem::create('Submenu', null, 'javascript:void(0);'),
                MenuItem::create('Submenu', null, 'javascript:void(0);')
            ]
        );

        // Menú de Email
        $emailMenu = MenuItem::create(
            'Menu',
            'bx bx-envelope',
            'javascript:void(0);'
        );

        // Menú desplegable de Pages con submenús anidados
        $pagesMenu = MenuItem::create(
            'Dropdown Menu',
            'bx bx-dock-top',
            'javascript:void(0);',
            [
                MenuItem::create(
                    'Dropdown',
                    null,
                    'javascript:void(0);',
                    [
                        MenuItem::create('Menu item', null, 'javascript:void(0);'),
                        MenuItem::create('Menu item', null, 'javascript:void(0);'),
                        MenuItem::create('Menu item', null, 'javascript:void(0);'),
                        MenuItem::create('Menu item', null, 'javascript:void(0);')
                    ]
                ),
                MenuItem::create('Menu', null, 'javascript:void(0);')
            ]
        );

        // Menú de Soporte
        $supportMenu = MenuItem::create(
            'Soporte',
            'bx bx-support',
            'https://hardboxs.com/contactanos/',
            [],
            false,
            true // External link
        );

        // Menú de Documentación
        $documentationMenu = MenuItem::create(
            'Documentacion',
            'bx bx-file',
            'javascript:void(0);'
        );

        // Construir el menú completo con separadores
        $builder
            ->addItem($dashboardsMenu)
            ->addItem($frontPagesMenu)
            ->addSeparator('Apps & Pages')
            ->addItem($emailMenu)
            ->addItem($pagesMenu)
            ->addSeparator('Misc')
            ->addItem($supportMenu)
            ->addItem($documentationMenu);

        return $builder->build();
    }
}
