<?php

namespace app\services\ui\builder;

use app\models\ui\menu\BadgeMenuItem;
use app\models\ui\menu\MenuBuilder;

class UIAsistenteMenu implements UIMenuInterface
{
    private $html = '';
    private $appPath;
    public function __construct()
    {
        $this->appPath = APP_DIRECTORY_PATH;
        $this->build();
    }

    public function build(): void
    {
        $menuBuilder = new MenuBuilder();
        // $menuBuilder->addItem(new SimpleMenuItem('Nuevos registros', 'fas fa-file-alt', $this->appPath . '/nuevosRegistros'))
        $menuBuilder->addItem(new BadgeMenuItem('Lotes producción', 'fas fa-box', $this->appPath . '/lotesProduccion', '?', 'badge-warning'));
        $menuBuilder->addItem(new BadgeMenuItem('Lotes productos', 'fas fa-vials', $this->appPath . '/lotesProductos', '?', 'badge-warning'));
        $menuBuilder->addItem(new BadgeMenuItem('Inventario', 'fas fa-boxes', $this->appPath . '/inventario', '?', 'badge-warning'));
        // ->addItem(
        //     (new SubmenuItem('Dashboard', 'fas fa-tachometer-alt'))
        //         ->addChild(child: new SimpleMenuItem('Dashboard v1', 'far fa-circle', $this->appPath . '/dashboard'))
        //         ->addChild(new SimpleMenuItem('Dashboard v2', 'far fa-circle', $this->appPath . '/dashboard'))
        //         ->addChild(new SimpleMenuItem('Dashboard v3', 'far fa-circle', $this->appPath . '/dashboard'))
        // );
        $this->html = $menuBuilder->build();
    }
    public function __toString(): string
    {
        return $this->html;
    }
}
