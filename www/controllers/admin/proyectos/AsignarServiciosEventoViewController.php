<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\models\Project;
use app\services\admin\vendedores\ui\UIProductosService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsProyectoService;

class AsignarServiciosEventoViewController  extends SneatAdvancedController implements PageBuilder
{
    private $args;
    private $eventoId;
    private $evento;
    private $servicios;
    private UIProductosService $uiService;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->args = $args;
        $this->uiService = new UIProductosService();
        $this->setLayoutName('admin.proyectos.serviciosEvento');
        $this->setRequest($this->args["request"]);
        $this->setSession($this->args["request"]->session());
        $this->setData($this->args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
    }
    private function initModels()
    {
        try {
            $this->eventoId = $this->getData()['evento_id'];
            $this->evento = Project::getProjectDataById($this->eventoId);
        } catch (\Exception $e) {
        }
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addLink($this->routes->search('adminDashboardView')->getUrl(), 'Home')
            ->addLink($this->routes->getUrlFor('eventosView'), 'Eventos')
            ->addLink(
                $this->routes->getUrlFor(
                    'eventoDetalleView',
                    ['proyecto' => $this->eventoId]
                ),
                $this->evento->nombre
            )
            ->addActive('Servicios'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => $this->evento->nombre,
            'subtitle' => 'Gestion de servicios',
            'eventoId' => $this->eventoId,
            'btnCancelar' => $this->routes->getUrlFor('eventosView'),
            'serviciosDisponibles' => '',
            'serviciosSeleccionados' => '',
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
