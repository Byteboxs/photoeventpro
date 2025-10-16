<?php

namespace app\controllers\admin\vendedores;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\services\admin\vendedores\ui\UIVendedoresService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;

class UIRegistrarVendedor extends SneatAdvancedController implements PageBuilder
{
    private UIVendedoresService $uiService;
    private $args;
    private array $vendedores = [];
    private $page = 1;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIVendedoresService();
        $this->args = $args;
        $this->setLayoutName('admin.vendedores.registrar');
        $this->setRequest($this->args["request"]);
        $this->setSession($this->args["request"]->session());
        $this->setData($this->args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addLink($this->routes->search('adminDashboardView')->getUrl(), 'Home')
            ->addLink($this->routes->search('vendedoresView')->getUrl(), 'Vendedores')
            ->addActive('Registrar'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Vendedores',
            'subtitle' => 'Registrar vendedor',
            'btnRegistrar' => $this->uiService->getBtnRegistrar(),
            'btnCancelar' => $this->uiService->getLinkCancelar($this->routes->search('vendedoresView')->getUrl()),
            'cargImg' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => $this->uiService->getForm(),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
