<?php

namespace app\controllers\admin\clientes;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\models\Document_type;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsRegisterCustomerService;

class UIFormCrearCLienteController extends UISneatTemplateController implements IDrawable
{
    public function __construct()
    {
        parent::__construct();
    }

    public function draw(...$args)
    {
        $documentoModel = new Document_type();
        $result = $documentoModel->findWhere(['status' => 'activo']);
        $documentsType = ModelToUIHelper::make()->formatDataForSelect($result, 'id', 'nombre');

        $formArgs = [
            'tiposDocumento' => $documentsType,
        ];

        $request = $args[0];
        $session = $request->session();
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-clientes', 'Clientes')
            ->addActive('Registrar Cliente');
        $contentView = new View('admin.clientes.registrar', [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIFormsRegisterCustomerService::make()->getForm($formArgs),
            'linkCancelBtn' => APP_DIRECTORY_PATH . '/lista-de-clientes',
        ]);
        $template = $this->create($session, $breadCrumb, $contentView);
        $this->response->view($template)->send();
    }
}
