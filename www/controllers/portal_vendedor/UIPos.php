<?php

namespace app\controllers\portal_vendedor;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\helpers\RouteHelper;
use app\models\Categoria;
use app\models\Document_type;
use app\models\Pricing_plan;
use app\models\Service;
use app\services\portal_vendedor\ui\UIPortalVendedorService;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;

class UIPos extends SneatAdvancedController implements PageBuilder
{
    private UIPortalVendedorService $uiService;
    private $args;
    private array $eventos = [];
    private $page = 1;
    private array $categorias = [];
    private array $productos = [];
    private int $evento_id;
    private array $documentsType = [];
    private int $salesPersonId;
    private $pricingPlan;

    public function __construct(...$args)
    {
        parent::__construct();
        $this->uiService = new UIPortalVendedorService();
        $this->args = $args;
        $this->setLayoutName('portal_vendedor.pos');
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
            ->addLink(RouteHelper::getUrlFor('salesPersonDashboardView'), 'Home')
            ->addLink(RouteHelper::getUrlFor('seleccionarEventoView'), 'Mis Eventos')
            ->addActive('Punto de venta'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'productos' => $this->uiService->getProductosGrid($this->productos, $this->pricingPlan),
            'event_id' => $this->evento_id,
            'sales_person_id' => $this->salesPersonId,
            // 'title' => 'Punto de venta',
            // 'subtitle' => 'Bienvenido al punto de venta',
            // 'btnRegistrar' => $this->uiService->getBtnRegistrar(),
            // 'btnCancelar' => $this->uiService->getLinkCancelar($this->routes->search('vendedoresView')->getUrl()),
            // 'cargImg' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'newCustomerForm' => $this->uiService->getNewCustomerForm($this->evento_id, $this->documentsType),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        try {
            $this->evento_id = $this->getData()['evento_id'] ?? null;
            $this->pricingPlan = Pricing_plan::getEventPricingPlan($this->evento_id);
            // echo '<pre>';
            // var_dump($this->pricingPlan->price);
            // echo '</pre>';
            $documentoModel = new Document_type();
            $result = $documentoModel->findWhere(['status' => 'activo']);
            $this->documentsType = ModelToUIHelper::make()->formatDataForSelect($result, 'id', 'nombre');

            $this->categorias = Categoria::getCategorias();
            $this->productos = Service::getAllServices();

            $this->salesPersonId = $this->getSession()->get('userId');
        } catch (\Exception $e) {

            echo $e->getMessage();
        }
        // echo '<pre>';
        // var_dump($this->evento_id);
        // echo '</pre>';
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
