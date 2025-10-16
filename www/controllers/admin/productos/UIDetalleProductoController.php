<?php

namespace app\controllers\admin\productos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\models\Service;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use NumberFormatter;

class UIDetalleProductoController extends UISneatTemplateController implements IDrawable
{
    private $content;
    private $breadcrumb;
    private $view;
    public function __construct()
    {
        parent::__construct();
    }

    private function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/listado-de-productos', 'Productos')
            ->addActive($args['producto']->nombre);
    }

    private function initContent(array $args = [])
    {
        $precio = floatval($args['producto']->precio);

        $formateador = new NumberFormatter("es-CO", NumberFormatter::CURRENCY);
        $precio = $formateador->formatCurrency($precio, "COP");

        $this->content = new View('admin.productos.detalle', [
            'producto' => $args['producto'],
            'precio' => $precio,
            'title' => $args['producto']->nombre,
            'subtitle' => $args['producto']->descripcion,
            'actionLink' => APP_DIRECTORY_PATH . '/editar-producto/' . $args['producto']->service_id,
            'actionClass' => 'btn-warning',
            'icon' => 'fas fa-edit',
            'actionText' => 'Editar',
        ]);
    }
    private function initView($session)
    {
        $this->view = $this->create($session, $this->breadcrumb, $this->content);
    }

    public function draw(...$args)
    {
        $request = $args["request"];
        $session = $request->session();
        $data = $request->getData();
        $producto_id = $data['producto'];
        $model = new Service();
        $producto = $model->getService($producto_id);
        $this->initBreadcrumb(['producto' => $producto]);
        $this->initContent(['producto' => $producto]);
        $this->initView($session);
        $this->response->view($this->view)->send();
    }
}
