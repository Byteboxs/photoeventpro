<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\services\ui\paginator\Paginator;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Project;
use app\services\strategies\CustomersEventStrategy;
use app\services\UICustomersEventTableService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\HtmlFactory;
use app\services\ui\table\BootstrapTable;


class UIDetalleProyectoController extends UISneatTemplateController implements IDrawable
{
    private $content;
    private $view;
    private $breadcrumb;
    private int $proyecto_id;
    private $project;
    private $projectData;
    private $customers;
    private BootstrapTable $table;
    private Paginator $paginator;
    private string $paginatorLink;
    private $page;
    private $linkRegistrarCliente;
    private $linkPagoEfectivo;
    public function __construct()
    {
        parent::__construct();
    }

    private function initData($proyecto_id, $page = 1)
    {
        $model = new Project();
        $this->project = $model->find($proyecto_id);
        $this->customers = $this->project->getCustomers($page);
        $this->projectData = $this->project->getProjectData();
        $this->linkRegistrarCliente = APP_DIRECTORY_PATH . '/registrar-cliente-evento/' . $proyecto_id;
        $this->linkPagoEfectivo = APP_DIRECTORY_PATH . '/evento/pos/efectivo/' . $proyecto_id;
    }

    private function initPaginator($customers, $page)
    {
        $this->paginatorLink = APP_DIRECTORY_PATH . '/evento/detalle/' . $this->proyecto_id . '/page/';
        $this->paginator = new Paginator($page, $this->paginatorLink, $customers['currentPage'], $customers['totalPages'], $customers['perPage'], $customers['totalData']);
    }

    private function initTable($customers)
    {
        $routes = RouteHelper::make();
        $eventosPagoClienteEfectivoView = $routes->search('eventosPagoClienteEfectivoView');
        $eventoClienteDetalleView = $routes->search('eventoClienteDetalleView');

        $idKey = 'customer_id';
        $tableId = 'customersEventTable';
        $this->table = UICustomersEventTableService::make(
            $customers,
            new CustomersEventStrategy(),
            $idKey,
            $tableId,
            [
                $eventoClienteDetalleView->getMethod() => $eventoClienteDetalleView->getUrl(['idEvento' => $this->proyecto_id]),
                'linkCargarImagenes' => APP_DIRECTORY_PATH . '/evento/' . $this->proyecto_id . '/cliente/{id}/cargar-imagenes',
                $eventosPagoClienteEfectivoView->getMethod() => $eventosPagoClienteEfectivoView->getUrl(['idEvento' => $this->proyecto_id]),
                'idEvento' => $this->proyecto_id
            ]
        )->get();
    }

    public function getLinkAdmVendedores()
    {
        $url = RouteHelper::getUrlFor('eventoVendedoresView', ['evento_id' => $this->proyecto_id]);
        return HtmlFactory::create('link', [
            'class' => 'btn btn-label-info',
            'href' => $url,
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'top',
            'title' => 'Asignar vendedores',
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-users me-2 mx-2',
            ])
        )->addChild('Gestión vendedores');
    }
    public function getlinkAdmServicios()
    {
        $url = RouteHelper::getUrlFor('asignarServiciosEventoView', ['evento_id' => $this->proyecto_id]);
        return HtmlFactory::create('link', [
            'class' => 'btn btn-label-info',
            'href' => $url,
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'top',
            'title' => 'Asignar servicios',
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-box me-2 mx-2',
            ])
        )->addChild('Gestión servicios');
    }

    private function getLinkPos($url)
    {
        return HtmlFactory::create('a', [
            'href' => $url,
            'class' => 'btn btn-label-info',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Punto de venta pago en efectivo',
            'data-bs-placement' => 'top'
        ])
            ->addChild(
                HtmlFactory::create('i', ['class' => 'fas fa-cash-register mx-2'])
            )
            ->addChild('Pos');
    }

    private function initContent()
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addActive($this->projectData->nombre);

        $this->content = new View('admin.proyectos.detalle', [
            'table' => $this->table,
            'paginator' => $this->paginator,
            // 'linkRegistrarCliente' => $this->linkRegistrarCliente,
            'linkRegistrarCliente' => '',
            // 'linkPagoEfectivo' => $this->getLinkPos($this->linkPagoEfectivo),
            'linkPagoEfectivo' => '',
            'linkGestionServicios' => $this->getlinkAdmServicios(),
            'projectData' => $this->projectData,
            'linkVendedores' => $this->getLinkAdmVendedores(),
            // 'linkEditar' => APP_DIRECTORY_PATH . '/editar-cliente-evento/',
        ]);
    }
    private function initView($session)
    {
        $this->view = $this->create($session, $this->breadcrumb, $this->content);
    }

    public function draw(...$args)
    {
        $data = $args["request"]->getData();
        $this->proyecto_id = $data['proyecto'];
        $this->page = $data['page'] ?? 1;
        $this->initData($this->proyecto_id, $this->page);
        $this->initPaginator($this->customers, $this->page);
        $this->initTable($this->customers['data']);
        $session = $args["request"]->session();
        $this->initContent();
        $this->initView($session);
        // echo '<pre>';
        // var_dump($this->customers);
        // echo '</pre>';
        $this->response->view($this->view)->send();
    }
}
