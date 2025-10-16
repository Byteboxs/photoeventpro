<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\models\Customer;
use app\models\Project;
use app\models\Service;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\ui\html\HtmlFactory;
use app\services\ui\html\Icon;
use app\services\UIFormsCustomersService;

class UIEventosPagoClienteEfectivoController extends SneatAdvancedController implements PageBuilder
{
    private $proyecto;
    private $productos;
    private $cliente;
    private $clientes;
    private $idEvento = null;
    private $idCliente = null;
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('admin.proyectos.pos');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addLink(APP_DIRECTORY_PATH . '/evento/detalle/' . $this->idEvento, $this->proyecto->nombre_evento)
            ->addActive('Pos');
    }
    private function getLinkDetalleCliente()
    {
        $link = $this->routes->search('eventoClienteDetalleView')->getUrl(
            [
                'idEvento' => $this->idEvento,
                'idCliente' => $this->idCliente
            ]
        );
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => ' btn btn-label-info',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Ver detalles del cliente actual',
            'data-bs-placement' => 'left'
        ], new Icon(['class' => 'far fa-user mx-2']) . 'Ver cliente');
    }
    private function getLinkCancelar()
    {
        $link = $this->routes->search('eventoClienteDetalleView')->getUrl(
            [
                'idEvento' => $this->idEvento,
                'idCliente' => $this->idCliente
            ]
        );
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => ' btn btn-outline-danger',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Cancelar la venta y regresar a la ventana del cliente',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'far fa-times-circle mx-2']) . 'Cancelar venta');
    }
    private function getLinkRegistrarCliente()
    {
        $link = $this->routes->search('formularioAgregarClienteProyectoView')->getUrl(
            [
                'proyecto' => $this->idEvento
            ]
        );
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-warning',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Agregar un cliente al proyecto',
            'data-bs-placement' => 'bottom'
        ], new Icon(['class' => 'fas fa-user-plus mx-2']) . 'Registrar Cliente');
    }

    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'productos' => $args['productos'],
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIFormsCustomersService::make()->getHiddenCustomersForm($args['customer']),
            'linkCancelar' => $this->getLinkCancelar(),
            'linkRegistrarCliente' => $this->getLinkRegistrarCliente(),
            'linkDetalleCliente' => $this->getLinkDetalleCliente(),
            'project_id' => $this->idEvento,
            'isClienteIndividual' => true,
            'nombreCliente' => $this->cliente->nombre_completo,
            'titulo' => 'Pago en efectivo'
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        $this->idEvento = isset($this->getData()['idEvento']) ?? $this->getData()['idEvento'];
        $this->idCliente = isset($this->getData()['idCliente']) ?? $this->getData()['idCliente'];

        $proyectoModel = new Project();
        $this->proyecto = $proyectoModel->find($this->idEvento);

        $customerModel = new Customer();
        $customer = $customerModel->find($this->idCliente);
        $this->cliente = $customer->getUser();

        $model = new Service();
        $this->productos = $model->getProductosActivos($this->idEvento);

        // $this->clientes = [$this->getData()['idCliente'] => ];
    }
    public function draw(...$args)
    {
        $this->setRequest($args["request"]);
        $this->setSession($args["request"]->session());
        $this->setData($args["request"]->getData());

        $this->initModels();
        $this->initBreadcrumb([
            'proyecto' => [
                'id' => $this->getData()['idEvento'],
                'name' => $this->proyecto->nombre_evento,
            ],
            'cliente' => [
                'id' => $this->getData()['idCliente'],
                'name' => $this->cliente->nombre_completo,
                'numero_identificacion' => $this->cliente->numero_identificacion
            ]
        ]);
        $this->initContent(['proyecto' => $this->proyecto->toArray(), 'productos' => $this->productos, 'customer' => $this->getData()['idCliente']]);
        $this->initView();
        $this->response->view($this->getView())->send();
    }
}
