<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\helpers\RouteHelper;
use app\models\Institution;
use app\models\Location;
use app\models\Pricing_plan;
use app\models\Project;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsProyectoService;

class UIFormEditarEventoController extends SneatAdvancedController implements PageBuilder
{
    // private UIVendedoresService $uiService;
    private $args;
    private $eventoId;
    private $evento;
    private $eventStatusOptions;
    private $instututions;
    private $locations;
    private $pricingPlans;
    private $formData;

    public function __construct(...$args)
    {
        parent::__construct();
        // $this->uiService = new UIVendedoresService();
        $this->args = $args;
        $this->setLayoutName('admin.proyectos.update');
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
            ->addLink($this->routes->getUrlFor('eventosView'), 'Eventos')
            ->addLink(
                $this->routes->getUrlFor(
                    'eventoDetalleView',
                    ['proyecto' => $this->eventoId]
                ),
                $this->evento->nombre
            )
            ->addActive('Editar'));
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'title' => $this->evento->nombre,
            'subtitle' => 'Editar Evento',
            // 'btnUpdate' => $this->uiService->getBtnEditar(),
            'btnCancelar' => $this->routes->getUrlFor('eventosView'),
            // 'cargImg' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIFormsProyectoService::make()->editEventForm($this->formData),
        ]));
    }

    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }
    private function addRowToSelet(array &$array)
    {
        $array['crear'] = 'Crear';
        return $array;
    }

    private function initModels()
    {
        try {
            $this->eventoId = $this->getData()['evento_id'];
            $this->evento = Project::getProjectDataById($this->eventoId);

            $this->instututions = new Institution();
            $this->instututions = $this->instututions->findWhere(['status' => 'activo']);
            $this->instututions = ModelToUIHelper::make()->formatDataForSelect($this->instututions, 'id', 'nombre');
            $this->instututions = $this->addRowToSelet($this->instututions);

            $this->locations = new Location();
            $this->locations = $this->locations->findWhere(['status' => 'activo']);
            $this->locations = ModelToUIHelper::make()->formatDataForSelect($this->locations, 'id', 'nombre');
            $this->locations = $this->addRowToSelet($this->locations);

            // $this->pricingPlans = new Pricing_plan();
            // $this->pricingPlans = $this->pricingPlans->all();
            // $this->pricingPlans = ModelToUIHelper::make()->formatDataForSelect($this->pricingPlans, 'id', 'name');

            $this->eventStatusOptions = [
                'activo' => 'Activo',
                'cancelado' => 'Cancelado',
                'finalizado' => 'Finalizado',
            ];

            // echo '<pre>';
            // var_dump($this->evento);
            // echo '</pre>';

            $this->formData = [
                'current_event_id' => $this->eventoId,
                'current_event_name' => $this->evento->nombre,
                'current_start_date' => $this->evento->inicio,
                'current_end_date' => $this->evento->fin,
                'current_ceremony_time' => $this->evento->hora_ceremonia,
                'current_description' => $this->evento->descripcion,
                // 'current_pricing_plan' => $this->evento->pricing_plans_id,
                // 'pricing_plans_options' => $this->pricingPlans,
                'event_status_options' => $this->eventStatusOptions,
                'current_event_status' => $this->evento->estado,
                'institutions_options' => $this->instututions,
                'current_institution_id' => $this->evento->institution_id,
                'locations_options' => $this->locations,
                'current_location_id' => $this->evento->location_id,
                'current_event_address' => $this->evento->direccion,


            ];
        } catch (\Exception $e) {
            // $this->response->redirect()->route(RouteHelper::getUrlFor('vendedoresView'));

        }
    }

    public function draw(...$args)
    {
        $this->response->view($this->getView())->send();
    }
}
