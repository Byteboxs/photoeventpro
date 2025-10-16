<?php

namespace app\controllers\admin\productos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\models\Service;
use app\services\strategies\ProductsStrategy;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\paginator\Paginator;
use app\services\UIProductsTableService;

class UIProductosController extends UISneatTemplateController implements IDrawable
{
    private $content;
    private $view;
    public function __construct()
    {
        parent::__construct();
    }
    private function init($page)
    {
        $model = new Service();
        $result = $model->getProductos($page);

        $productos = $result['data'];
        $currentPage = $result['currentPage'];
        $totalPages = $result['totalPages'];
        $perPage = $result['perPage'];
        $totalData = $result['totalData'];

        $paginator = new Paginator($page, APP_DIRECTORY_PATH . '/listado-de-productos/page/', $currentPage, $totalPages, $perPage, $totalData);

        $table = UIProductsTableService::make($productos, new ProductsStrategy(), 'service_id', 'tableProjects', [
            'tplPath' => $this->tplPath,
            'linkVer' => APP_DIRECTORY_PATH . '/producto/detalle/',

        ])->get();
        $this->content = new View('admin.productos.listado', [
            'title' => 'Gestionar productos',
            'subtitle' => 'Lorem ipsum',
            'actionLink' => APP_DIRECTORY_PATH . '/crear-producto',
            'actionText' => 'Crear producto',
            'icon' => 'fas fa-plus-square',
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'actionClass' => 'btn btn-label-warning',
            'appPath' => APP_DIRECTORY_PATH,
            'table' => $table,
            'paginator' => $paginator
        ]);
    }
    public function draw(...$args)
    {
        $request = $args["request"];
        $session = $request->session();
        $data = $request->getData();
        $page = $data['page'] ?? 1;
        $this->init($page);
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            // ->addLink('#', 'Link')
            ->addActive('Productos');
        $this->view = $this->create($session, $breadCrumb, $this->content);
        $this->response->view($this->view)->send();
    }
}
