<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\helpers\RouteHelper;
use app\models\Project;
use app\models\Service;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\ui\html\HtmlFactory;
use app\services\ui\html\Icon;
use app\services\UIFormsCustomersService;

class UIPosEventoController extends UISneatTemplateController implements IDrawable
{
    protected $routes;
    private $content;
    private $breadcrumb;
    private $view;
    private $idEvento;
    private $request;
    private $session;
    private $data;
    public function __construct()
    {
        parent::__construct();
        $this->routes = new RouteHelper();
    }

    private function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addLink(APP_DIRECTORY_PATH . '/evento/detalle/' . $args['id'], $args['nombre_evento'])
            ->addActive('Pos');
    }

    private function getLinkCancelar()
    {
        $link = $this->routes->search('eventoDetalleView')->getUrl(['proyecto' => $this->idEvento]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => ' btn btn-outline-danger',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Cancelar la venta y regresar a la ventana de proyecto',
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
            'data-bs-placement' => 'left'
        ], new Icon(['class' => 'fas fa-user-plus mx-2']) . 'Registrar Cliente');
    }

    private function initContent(array $args = [])
    {
        $this->content = new View('admin.proyectos.pos', [
            'productos' => $args['productos'],
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIFormsCustomersService::make()->getSelectCustomersForm($args['customers']),
            // 'linkCrearCliente' => APP_DIRECTORY_PATH . '/registrar-cliente-evento/' . $this->idEvento,
            'linkRegistrarCliente' => $this->getLinkRegistrarCliente(),
            'linkCancelar' => $this->getLinkCancelar(),
            'project_id' => $args['proyecto']['id'],
            'titulo' => 'Pago en efectivo',
            // 'producto' => $args['producto'],
            // 'precio' => $precio,
            // 'title' => $args['producto']->nombre,
            // 'subtitle' => $args['producto']->descripcion,
            // 'actionLink' => APP_DIRECTORY_PATH . '/editar-producto/' . $args['producto']->service_id,
            // 'actionClass' => 'btn-warning',
            // 'icon' => 'fas fa-edit',
            // 'actionText' => 'Editar',
        ]);
    }

    private function initView()
    {
        $this->view = $this->create($this->session, $this->breadcrumb, $this->content);
    }

    private function initRequest($request)
    {
        $this->request = $request;
        $this->session = $request->session();
        $this->data = $request->getData();
    }

    public function draw(...$args)
    {
        $this->initRequest($args["request"]);
        $this->idEvento = $this->data['proyecto'];
        $model = new Project();
        $proyecto = $model->find($this->idEvento);
        $customers = $proyecto->getAllCustomers();
        $customers = ModelToUIHelper::make()->formatDataForSelect($customers, 'customer_id', 'nombre_completo');
        $model = new Service();
        $productos = $model->getProductosActivos($this->idEvento);
        $this->initBreadcrumb($proyecto->toArray());
        $this->initContent(['proyecto' => $proyecto->toArray(), 'productos' => $productos, 'customers' => $customers]);
        $this->initView();
        $this->response->view($this->view)->send();
    }
}
