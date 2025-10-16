<?php

namespace app\controllers\customer\store;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\models\Customer;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\HtmlServiceCardPanelCreator;

class UIServiciosController extends SneatAdvancedController implements PageBuilder
{
    private $orderDetails = [];
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('customer.store.content');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-cliente', 'Home')
            ->addActive('Servicios');
    }
    private function createServices(?array $orderDetails)
    {
        return new HtmlServiceCardPanelCreator($orderDetails);
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'servicios' => $this->createServices($this->orderDetails)
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }
    private function initModels()
    {
        $session = $this->getSession();
        $this->orderDetails = Customer::getServicesInActiveProjectByUserId($session->get('userId'));
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
}
