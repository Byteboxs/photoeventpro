<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\models\Customer;
use app\models\Project;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\HtmlFactory;
use app\services\ui\html\Icon;

class UICargarImagenesClienteController extends SneatAdvancedController implements PageBuilder
{
    private $proyecto;
    private $idEvento;
    private $cliente;
    private $idCliente;
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('admin.proyectos.clientes.cargar-imagenes');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addLink(APP_DIRECTORY_PATH . '/evento/detalle/' . $args['proyecto']['id'], $args['proyecto']['name'])
            ->addActive($args['cliente']['name']));
    }
    private function getLinkPagoEfectivo()
    {
        $link = $this->routes->search('eventosPagoClienteEfectivoView')->getUrl(['idEvento' => $this->idEvento, 'idCliente' => $this->idCliente]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-info',
            // 'target' => '_self',
            'target' => '_blank',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Pago en efectivo',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'fas fa-cash-register mx-2']) . 'Pagar');
    }

    private function getLinkCargarImagenes()
    {
        $link = $this->routes->search('cargarImagenesClienteView')->getUrl(['proyecto' => $this->idEvento, 'id' => $this->idCliente]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-info',
            'target' => '_blank',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Subir las imagenes del cliente al servidor',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'fas fa-file-upload mx-2']) . 'Cargar');
    }

    private function getLinkeventoClienteDetalleView()
    {
        $link = $this->routes->search('eventoClienteDetalleView')->getUrl([
            'idEvento' => $this->idEvento,
            'idCliente' => $this->idCliente
        ]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-info',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Ver detalles del cliente actual',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'far fa-user mx-2']) . 'Ver cliente');
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'proyecto_id' => $this->getData()['proyecto'],
            'cliente_id' => $this->getData()['id'],
            'linkPagoEfectivo' => $this->getLinkPagoEfectivo(),
            'linkCargarImagenes' => $this->getLinkCargarImagenes(),
            'linkEventoClienteDetalle' => $this->getLinkeventoClienteDetalleView(),
            'titulo' => 'Cargar imagenes de ' . $this->cliente->nombre_completo
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        $this->idEvento = $this->getData()['proyecto'];
        $this->idCliente = $this->getData()['id'];
        $proyectoModel = new Project();
        $this->proyecto = $proyectoModel->find($this->getData()['proyecto']);

        $customerModel = new Customer();
        $customer = $customerModel->find($this->getData()['id']);
        $this->cliente = $customer->getUser();
    }
    public function draw(...$args)
    {
        $this->setRequest($args["request"]);
        $this->setSession($args["request"]->session());
        $this->setData($args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb([
            'proyecto' => [
                'id' => $this->getData()['proyecto'],
                'name' => $this->proyecto->nombre_evento,
            ],
            'cliente' => [
                'id' => $this->getData()['id'],
                'name' => $this->cliente->nombre_completo,
                'numero_identificacion' => $this->cliente->numero_identificacion
            ]
        ]);
        $this->initContent();
        $this->initView();
        $this->response->view($this->getView())->send();
    }
}
