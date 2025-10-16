<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Project;
use app\models\Project_salespeople;
use app\services\admin\vendedores\ui\UIVendedoresService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class UIEventoVendedores extends SneatAdvancedController implements PageBuilder
{
    private $args;
    private $evento_id;
    private $evento;
    private array $vendedoresDisponibles = [];
    private array $vendedoresSeleccionados = [];
    private $page = 1;
    private UIVendedoresService $uiService;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIVendedoresService();
        $this->args = $args;
        $this->setLayoutName('admin.proyectos.vendedores');
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
            ->addLink(RouteHelper::getUrlFor('adminDashboardView'), 'Home')
            ->addLink(RouteHelper::getUrlFor('eventosView'), 'Eventos')
            ->addLink(RouteHelper::getUrlFor('eventoDetalleView', ['proyecto' => $this->evento_id]), ucfirst(mb_strtolower($this->evento->nombre_evento)))
            // ->addLink($this->routes->search('vendedoresView')->getUrl(), 'Vendedores')
            ->addActive('Asignar vendedores'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Evento',
            'subtitle' => 'Asignar vendedores',
            'eventoId' => $this->evento_id,
            'vendedoresDisponibles' => $this->uiService->getEevntoVendedoresCards($this->vendedoresDisponibles['data']),
            'vendedoresSeleccionados' => $this->uiService->getEevntoVendedoresCards($this->vendedoresSeleccionados['data'], 'seleccionados'),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $this->evento_id = $this->getData()['evento_id'];
            $model = new Project();
            $this->evento = $model->find($this->evento_id);
            if (!$this->evento) {
                $this->response->redirect()->route(RouteHelper::getUrlFor('eventosView'));
            }
            $this->page = $this->getData()['page'] ?? 1;
            $this->vendedoresDisponibles = Project_salespeople::getVendedoresDisponiblesByEvento($this->evento_id, $this->page);
            $this->vendedoresSeleccionados = Project_salespeople::getVendedoresSeleccionadosByEvento($this->evento_id, $this->page);
        } catch (\Exception $e) {
            // $this->response->redirect()->route(RouteHelper::getUrlFor('eventosView'));
            // echo 'Error: ' . $e->getMessage();
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
