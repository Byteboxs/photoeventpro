<?php

namespace app\controllers\admin\productos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\models\Categoria;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsProductoService;

class UIAgregarProductoController  extends UISneatTemplateController implements IDrawable
{
    private $breadcrumb;
    private $content;
    private $view;
    private $form;
    public function __construct()
    {
        parent::__construct();
        $this->initBreadcrumb();
        $this->initForm();
        $this->initContent();
    }

    private function initForm()
    {
        $model = new Categoria();
        $categorias = $model->findWhere(['status' => 'activo']);
        $categorias = ModelToUIHelper::make()->formatDataForSelect($categorias, 'id', 'nombre');
        $args = ['categorias' => $categorias];
        $this->form = UIFormsProductoService::make()->get($args);
    }
    private function initBreadcrumb()
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/listado-de-productos', 'Productos')
            ->addActive('Agregar');
    }
    private function initContent()
    {
        $this->content = new View('admin.productos.crear', [
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => $this->form,
            'linkCancelBtn' => APP_DIRECTORY_PATH . '/listado-de-productos',
            'tooltipCancelBtn' => 'Haga click para cancelar la creación de un nuevo producto',
            'tooltipSaveBtn' => 'Haga click para crear un nuevo producto'

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
        $this->initView($session);
        $this->response->view($this->view)->send();
    }
}
