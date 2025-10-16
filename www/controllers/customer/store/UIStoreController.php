<?php

namespace app\controllers\customer\store;

use app\controllers\template\UISneatTemplateController;
use app\core\http\Request;
use app\core\views\View;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class UIStoreController extends UISneatTemplateController
{
    private $contentView;
    public function __construct()
    {
        parent::__construct();
        $this->contentView = new View('customer.store.content', [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
        ]);
    }

    public function listadoProductosView(Request $request)
    {
        $session = $request->session();
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-cliente', 'Home')
            // ->addLink('#', 'Link')
            ->addActive('Store');
        $template = $this->create($session, $breadCrumb, $this->contentView);
        $this->response->view($template)->send();
    }
}
