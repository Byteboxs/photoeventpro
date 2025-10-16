<?php

namespace app\controllers\admin\productos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\models\Categoria;
use app\models\Service;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsProductoService;

class UIEditarProductoController  extends UISneatTemplateController implements IDrawable
{
    private $breadcrumb;
    private $content;
    private $view;
    private $form;
    private $producto_id;
    private $producto;

    public function __construct()
    {
        parent::__construct();
    }


    private function initForm(array $args = [])
    {
        $model = new Categoria();
        $categorias = $model->findWhere(['status' => 'activo']);
        $categorias = ModelToUIHelper::make()->formatDataForSelect($categorias, 'id', 'nombre');
        $args['categorias'] = $categorias;
        $this->form = UIFormsProductoService::make()->get($args);
    }
    private function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/listado-de-productos', 'Productos')
            ->addLink(APP_DIRECTORY_PATH . '/producto/detalle/' . $args['producto']->id, $args['producto']->nombre)
            ->addActive('Editar');
    }
    private function initContent(array $args = [])
    {
        $this->content = new View('admin.productos.editar', [
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => $this->form,
            'linkCancelBtn' => APP_DIRECTORY_PATH . '/producto/detalle/' . $args['producto']->id,
            'tooltipCancelBtn' => 'Haga click para cancelar la edición de un nuevo producto',
            'tooltipSaveBtn' => 'Haga click para editar',
            'title' => $args['producto']->nombre

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
        $productoModel = new Service();

        $this->producto = $productoModel->find($producto_id);
        if ($this->producto) {
            $this->producto = $this->producto->toPlainObject();
            // var_dump($this->producto);
        }

        $this->initBreadcrumb(['producto' => $this->producto]);
        $this->initForm(['producto' => $this->producto, 'activarProducto' => true]);
        $this->initContent(['producto' => $this->producto]);
        $this->initView($session);
        $this->response->view($this->view)->send();
    }
}
