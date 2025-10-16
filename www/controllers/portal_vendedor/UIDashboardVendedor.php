<?php

namespace app\controllers\portal_vendedor;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\services\portal_vendedor\ui\UIPortalVendedorService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class UIDashboardVendedor extends SneatAdvancedController implements PageBuilder
{
    private UIPortalVendedorService $uiService;
    private $args;
    private array $eventos = [];
    private $page = 1;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIPortalVendedorService();
        $this->args = $args;
        $this->setLayoutName('portal_vendedor.dashboard');
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
            // ->addLink(RouteHelper::getUrlFor('salesPersonDashboardView'), 'Home')
            // ->addLink($this->routes->search('vendedoresView')->getUrl(), 'Vendedores')
            ->addActive('Home'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Portal',
            'subtitle' => 'Bienvenido al portal vendedor',
            'imgPath' => $this->imgPath,
            'appPath' => APP_DIRECTORY_PATH,
            // 'btnRegistrar' => $this->uiService->getBtnRegistrar(),
            // 'btnCancelar' => $this->uiService->getLinkCancelar($this->routes->search('vendedoresView')->getUrl()),
            // 'cargImg' => $this->imgPath,
            // 'renderer' => new Bootstrap5FormRenderer(),
            // 'form' => $this->uiService->getForm(),
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
