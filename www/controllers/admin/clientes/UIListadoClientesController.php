<?php

namespace app\controllers\admin\clientes;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class UIListadoClientesController extends UISneatTemplateController implements IDrawable
{
    private $contentView;
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $this->contentView = new View('admin.clientes.listado', [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'linkAgregarCliente' => APP_DIRECTORY_PATH . '/registrar-cliente',
        ]);
    }

    public function draw(...$args)
    {
        $session = $args[0]->session();
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            // ->addLink('#', 'Link')
            ->addActive('Clientes');
        $template = $this->create($session, $breadCrumb, $this->contentView);
        $this->response->view($template)->send();
    }
}
