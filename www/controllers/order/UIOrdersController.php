<?php

namespace app\controllers\order;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\database\builder\Criteria;
use app\core\views\View;
use app\models\Order_detail;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\paginator\Paginator;
use app\services\UIOrderTableService;

class UIOrdersController extends SneatAdvancedController implements PageBuilder
{
    private $args;
    private array $orders = [];
    private $page = 1;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->args = $args;
        $this->setLayoutName('admin.orders.list');
        $this->setRequest($this->args["request"]);
        $this->setSession($this->args["request"]->session());
        $this->setData($this->args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
    }

    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addLink($this->routes->search('adminDashboardView')->getUrl(), 'Home')
            ->addActive('Pedidos'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'table' => $this->getTable($this->orders['data']),
            'paginator' => $this->getPaginator($this->orders),
            'selfLink' => $this->routes->search('ordersView')->getUrl(),
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $data = $this->getData();
            $this->page = $data['page'] ?? 1;
            $criteria = null;
            if (isset($data["filter"]) && $data["filter"] != "-1" && isset($data["option"]) && $data["option"] != "") {
                $opcion = $data["option"];
                if ($data["filter"] == "nombre") {

                    $criteria = Criteria::create()
                        ->and(Criteria::like("CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido,
                        IFNULL(CONCAT(' ', u.segundo_apellido), ''))", "%{$opcion}%", false));
                }
                if ($data["filter"] == "documento") {
                    $criteria = Criteria::create()
                        ->and(Criteria::equals("u.numero_identificacion", $opcion));
                }
            }
            $this->orders = Order_detail::getAllOrderDetailInfo($criteria, $this->page);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getPaginator($result)
    {
        return new Paginator(
            $this->page,
            APP_DIRECTORY_PATH . '/orders/page/',
            $result['currentPage'],
            $result['totalPages'],
            $result['perPage'],
            $result['totalData'],
        );
    }

    private function getTable($customers)
    {
        return (new UIOrderTableService($customers))->get();
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
