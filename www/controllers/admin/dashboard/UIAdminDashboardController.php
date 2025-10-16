<?php

namespace app\controllers\admin\dashboard;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\controllers\template\UISneatTemplateController;
use app\core\http\Request;
use app\core\views\View;
use app\models\Project;
use app\models\Purchase_order;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\dashboard\HtmlKpi;

class UIAdminDashboardController extends SneatAdvancedController implements PageBuilder
{
    private $contentView;
    private $KPIs = [];
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('admin.dashboard.content');
    }

    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addActive('Home'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'appPath' => APP_DIRECTORY_PATH,
            'KPIs' => $this->KPIs
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        $ordersPendingPrint = Purchase_order::getNumberOfOrdersPendingPrint();
        $this->KPIs = [
            new HtmlKpi(
                "fas fa-project-diagram",
                "Nuevos Proyectos",
                '' . Project::getNumberOfNewActiveProjects(),
                "primary"
            ),
            new HtmlKpi(
                "fas fa-print",
                "Servicios pendientes de impresión",
                '' . $ordersPendingPrint,
                $ordersPendingPrint == 0 ? "success" : ($ordersPendingPrint > 20 ? "danger" : "warning")
            ),
        ];
    }
    public function draw(...$args)
    {
        $this->setRequest($args["request"]);
        $this->setSession($args["request"]->session());
        $this->setData($args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
        $this->response->view($this->getView())->send();
    }

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->contentView = new View('admin.dashboard.content', [
    //         'tplPath' => $this->tplPath,
    //         'imgPath' => $this->imgPath,
    //         'appPath' => APP_DIRECTORY_PATH
    //     ]);
    // }

    // public function adminDashboardView(Request $request)
    // {
    //     $session = $request->session();
    //     $breadCrumb = BreadcrumbBuilderService::create()
    //         // ->addLink('#', 'Home')
    //         // ->addLink('#', 'Link')
    //         ->addActive('Home');
    //     $template = $this->create($session, $breadCrumb, $this->contentView);
    //     $this->response->view($template)->send();
    // }
}
