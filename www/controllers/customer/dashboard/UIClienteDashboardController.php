<?php

namespace app\controllers\customer\dashboard;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\http\Request;
use app\core\views\View;
use app\models\Customer;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\ConfigureServiceCard;

class UIClienteDashboardController extends SneatAdvancedController implements PageBuilder
{
    private $orderDetails;
    private $numServices = 0;
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('customer.dashboard.content');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            // ->addLink(APP_DIRECTORY_PATH . '/dashboard-cliente', 'Home')
            ->addActive('Home');
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'services' => $this->getCards(),
            'numServices' => $this->numServices,
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
        $this->numServices = count($this->orderDetails);
    }
    private function createServiceCard(\stdClass $data) {}
    public function draw(...$args)
    {
        $this->setRequest($args["request"]);
        $this->setSession($args["request"]->session());
        $this->setData($args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
        // echo '<pre>';
        // var_dump($this->orderDetails);
        // echo '</pre>';
        $this->response->view($this->getView())->send();
    }
    private function getCards()
    {
        $html = '';
        foreach ($this->orderDetails as $order) {
            $card = new ConfigureServiceCard($order);
            $html .= $card;
        }
        return $html;
    }
}
