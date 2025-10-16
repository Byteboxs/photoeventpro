<?php

namespace app\controllers\demo;

use app\controllers\template\UISneatTemplateController;
use app\core\Application;
use app\core\http\Request;
use app\core\views\View;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIDemoFormService;
use app\services\UIDemoTableService;

class UIDemoController extends UISneatTemplateController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function uiDemoTplSneat(Request $request)
    {
        $breadCrumb = BreadcrumbBuilderService::create()
            // ->addLink('#', 'Home')
            // ->addLink('#', 'Link')
            ->addActive('Dashboard');

        $content = new View('demo.content', [
            'imgPath' => $this->imgPath,
            'tplPath' => $this->tplPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIDemoFormService::create(['1' => 'Cedula de Ciudadania'])->getForm(),
            'table' => UIDemoTableService::create([['id' => 1, 'nombre' => 'Luis', 'email' => 'luis@luis', 'acciones' => 'Acciones']])->getTable(),
        ]);

        $template = $this->create($breadCrumb, $content);
        $this->response->view($template)->send();
    }
}
