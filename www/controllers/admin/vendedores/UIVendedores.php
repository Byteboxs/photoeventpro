<?php

namespace app\controllers\admin\vendedores;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\database\builder\Criteria;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\User;
use app\services\admin\vendedores\ui\UIVendedoresService;
use app\services\admin\vendedores\ui\VendedoresTableService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\HtmlFactory;
use app\services\ui\paginator\Paginator;
use app\services\UIOrderTableService;

class UIVendedores extends SneatAdvancedController implements PageBuilder
{
    private $args;
    private array $vendedores = [];
    private $page = 1;
    private UIVendedoresService $uiService;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIVendedoresService();
        $this->args = $args;
        $this->setLayoutName('admin.vendedores.listado');
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
            ->addLink(RouteHelper::getUrlFor('adminDashboardView'), 'Home')
            ->addActive('Vendedores'));
    }
    public function initContent(array $args = [])
    {

        $this->setContent(new View($this->getLayoutName(), [
            'table' => $this->uiService->getTableVendedores($this->vendedores['data']),
            'paginator' => $this->uiService->getPaginatorVendedores($this->vendedores, $this->page),
            'selfLink' => RouteHelper::getUrlFor('vendedoresView'),
            'title' => 'Vendedores',
            'subtitle' => 'Listado de vendedores',
            'linkCreateVendedor' => $this->uiService->getLinkCreateVendedor(
                RouteHelper::getUrlFor('registrarVendedorView')
            ),
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
            if (isset($data["criteria"]) && $data["criteria"] != "-1" && isset($data["value"]) && $data["value"] != "") {
                $option = $data["value"];
                if ($data["criteria"] == "apellido") {
                    // $criteria = Criteria::like('u.primer_apellido', '%' . $option . '%');
                    $criteria = Criteria::create()
                        ->and(Criteria::like("CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido,
                        IFNULL(CONCAT(' ', u.segundo_apellido), ''))", "%{$option}%", false));
                }
                if ($data["criteria"] == "documento") {
                    $criteria = Criteria::equals("u.numero_identificacion", $option);
                }
            }
            $this->vendedores = User::getVendedores($criteria, $this->page);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
