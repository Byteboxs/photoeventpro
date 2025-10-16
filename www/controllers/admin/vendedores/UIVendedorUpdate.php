<?php

namespace app\controllers\admin\vendedores;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\User;
use app\services\admin\vendedores\ui\UIVendedoresService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;

class UIVendedorUpdate extends SneatAdvancedController implements PageBuilder
{
    private UIVendedoresService $uiService;
    private $args;
    private $user_id;
    private $user;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIVendedoresService();
        $this->args = $args;
        $this->setLayoutName('admin.vendedores.update');
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
            ->addActive('Editar'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Vendedores',
            'subtitle' => 'Vendedor: ' . $this->user->primer_nombre . ' ' . $this->user->primer_apellido,
            'btnUpdate' => $this->uiService->getBtnEditar(),
            'btnCancelar' => $this->uiService->getLinkCancelar($this->routes->search('vendedoresView')->getUrl(), 'update'),
            'cargImg' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => $this->uiService->getForm('editar', $this->user),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $this->user_id = $this->getData()['user_id'];
            $this->user = User::getSalesPersonByUserId($this->user_id);
            if (!$this->user) {
                $this->response->redirect()->route(RouteHelper::getUrlFor('vendedoresView'));
            }
        } catch (\Exception $e) {
            $this->response->redirect()->route(RouteHelper::getUrlFor('vendedoresView'));
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
