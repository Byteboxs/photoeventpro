<?php

namespace app\services\strategies;

use app\services\ui\badge\BadgeBuilderService;
use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;
use NumberFormatter;

class ProductsStrategy
{
    public function addControls($item, $args)
    {
        $id = $item[$args['idName']];
        $linkVer = $args['linkVer'] . $id;
        $estado = $item[$args['column']];
        $links = [
            'activo' => [
                ['link' => $linkVer, 'icon' => 'bx bx-link-external', 'text' => 'Ver'],
            ],
            'inactivo' => [
                ['link' => $linkVer, 'icon' => 'bx bx-link-external', 'text' => 'Ver inactivo'],
            ],
        ];
        $dropdown = new Dropdown();
        foreach ($links[$estado] as $link) {
            $dropdown->addItem(new LinkDropdownItem($link['link'], $link['icon'], $link['text']));
        }
        return $dropdown->render();
    }

    public function formatPrecio($item, $args)
    {
        $precio = $item[$args['column']];
        $formateador = new NumberFormatter("es-CO", NumberFormatter::CURRENCY);
        return $formateador->formatCurrency($precio, "COP");
    }

    public function modifyEstado($item, $args)
    {
        $estado = $item[$args['column']];
        if ($estado === 'activo') {
            $badge = BadgeBuilderService::make(
                'Activo',
                BadgeBuilderService::COLOR_SUCCESS,
                BadgeBuilderService::TYPE_DEFAULT
            );
            $badge->setText('Activo');
            $badge->setColor(BadgeBuilderService::COLOR_SUCCESS);
            $badge->setType(BadgeBuilderService::TYPE_DEFAULT);
            return $badge->render();
        } else if ($estado === 'inactivo') {
            $badge = BadgeBuilderService::make(
                'Inactivo',
                BadgeBuilderService::COLOR_SUCCESS,
                BadgeBuilderService::TYPE_DEFAULT
            );
            $badge->setText('Inactivo');
            $badge->setColor(BadgeBuilderService::COLOR_DANGER);
            $badge->setType(BadgeBuilderService::TYPE_DEFAULT);

            return $badge->render();
        }
    }
}
