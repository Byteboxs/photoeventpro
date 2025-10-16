<?php

namespace app\controllers\admin\vendedores;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\database\builder\Criteria;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Employe;
use app\models\User;
use app\services\admin\vendedores\ui\ReporteRendimientoVendedorService;
use app\services\admin\vendedores\ui\UIVendedoresService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;

class ReporteRendimientoVendedor extends SneatAdvancedController implements PageBuilder
{
    private ReporteRendimientoVendedorService $uiService;
    private $dataReporteRendimientoVendedor = [];
    private $page = 1;

    private $args;
    private $user_id;
    private $user;
    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new ReporteRendimientoVendedorService();
        $this->args = $args;
        $this->setLayoutName('admin.vendedores.rendimiento');
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
            ->addLink($this->routes->search('vendedoresView')->getUrl(), 'Vendedores')
            ->addActive('Rendimiento'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => 'Reporte rendimiento',
            'subtitle' => 'Vendedor: ' . $this->user->primer_nombre . ' ' . $this->user->primer_apellido,
            'table' => $this->uiService->getTable($this->dataReporteRendimientoVendedor['data']),
            'paginator' => $this->uiService->getPaginator($this->dataReporteRendimientoVendedor, $this->page),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $this->user_id = $this->getData()['user_id'];
            $this->page = $this->getData()['page'] ?? 1;
            var_dump($this->page);
            $criteria = Criteria::equals('U.id', $this->user_id);
            $this->user = User::getSalesPersonByUserId($this->user_id);

            if ($this->user) {
                $this->dataReporteRendimientoVendedor = Employe::getDatosRendimientoVendedor(
                    $criteria,
                    $this->page
                );
                // echo "<pre>";
                // print_r($this->dataReporteRendimientoVendedor['data']);
                // echo "</pre>";
            }

            if (!$this->user) {
                // $this->response->redirect()->route(RouteHelper::getUrlFor('vendedoresView'));
            }
        } catch (\Exception $e) {
            // $this->response->redirect()->route(RouteHelper::getUrlFor('vendedoresView'));
        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
