<?php

namespace app\controllers\portal_vendedor;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\database\builder\Criteria;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Project;
use app\services\portal_vendedor\ui\UIPortalVendedorService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class UISeleccionarEvento extends SneatAdvancedController implements PageBuilder
{
    private UIPortalVendedorService $uiService;
    private $args;
    private array $eventos = [];
    private int $user_id;
    private $page = 1;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIPortalVendedorService();
        $this->args = $args;
        $this->setLayoutName('portal_vendedor.eventos');
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
            ->addLink(RouteHelper::getUrlFor('salesPersonDashboardView'), 'Home')
            ->addActive('Mis Eventos'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Eventos',
            'subtitle' => 'Seleccione un evento para vender',
            'paginator' => $this->uiService->getPaginatorEventosDisponibles($this->eventos, $this->page),
            'table' => $this->uiService->getTableEventosDisponibles($this->eventos['data']),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $data = $this->getData();
            $this->page = $data['page'] ?? 1;
            $this->user_id = $this->getSession()->get('userId');
            $criteria = Criteria::equals('u.id', $this->user_id);
            $this->eventos = Project::getProjectsForSalesperson($criteria, $this->page);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
