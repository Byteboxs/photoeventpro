<?php

namespace app\controllers\admin\clientes;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\models\Document_type;
use app\models\Project;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\ui\form\Form;
use app\services\ui\html\HtmlFactory;
use app\services\UIFormsRegisterCustomerService;

class UIFormAgregarClienteProyectoController extends UISneatTemplateController implements IDrawable
{
    private ?Form $form = null;
    private int $proyecto_id = 0;
    private $content;
    private $view;
    private $breadcrumb;
    public function __construct()
    {
        parent::__construct();
    }

    private function initForm($proyecto_id)
    {
        $documentoModel = new Document_type();
        $result = $documentoModel->findWhere(['status' => 'activo']);
        $documentsType = ModelToUIHelper::make()->formatDataForSelect($result, 'id', 'nombre');
        $formArgs = [
            'project_id' => $proyecto_id,
            'tiposDocumento' => $documentsType,
        ];
        $this->form = UIFormsRegisterCustomerService::make()->getForm($formArgs);
    }

    private function getLinkCancelar($url)
    {
        return HtmlFactory::create('a', [
            'href' => $url,
            'class' => 'btn btn-danger',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Haga click cancelar y regresar a la pantalla anterior.',
        ], '<i class="far fa-times-circle mx-2"></i> Cancelar');
    }

    private function initContent($proyecto)
    {
        $linkVerProyecto = APP_DIRECTORY_PATH . '/evento/detalle/' . $this->proyecto_id;
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink($linkVerProyecto, 'Evento')
            ->addActive('Registrar Cliente');

        $this->content = new View('admin.clientes.registrar', [
            'nombreEvento' => $proyecto->nombre_evento,
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => $this->form,
            'tooltipSaveBtn' => 'Haga click para registrar un nuevo cliente',
            'linkCancel' => $this->getLinkCancelar($linkVerProyecto),
        ]);
    }

    private function initView($session)
    {
        $this->view = $this->create($session, $this->breadcrumb, $this->content);
    }

    public function draw(...$args)
    {
        $data = $args["request"]->getData();
        $session = $args["request"]->session();
        $this->proyecto_id = $data['proyecto'];
        $proyectoModel = new Project();
        $proyecto = $proyectoModel->find($this->proyecto_id);
        $this->initForm($this->proyecto_id);
        $this->initContent($proyecto);
        $this->initView($session);
        $this->response->view($this->view)->send();
    }
}
