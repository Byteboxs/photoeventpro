<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\helpers\ModelToUIHelper;
use app\models\Institution;
use app\models\Location;
use app\models\Pricing_plan;
use app\models\Project;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\UIFormsProyectoService;

class UIFormCrearEventoController extends UISneatTemplateController implements IDrawable
{
    public function __construct()
    {
        parent::__construct();
    }

    private function addRow(array &$array)
    {
        $array['crear'] = 'Crear';
        return $array;
    }

    public function draw(...$args)
    {
        $request = $args[0];
        $projectsModel = new Project();
        $instututionsModel = new Institution();
        $locationsModel = new Location();
        // $pricingPlansModel = new Pricing_plan();

        // $pricingPlans = $pricingPlansModel->all();
        // $pricingPlansFormattedForUi = ModelToUIHelper::make()->formatDataForSelect($pricingPlans, 'id', 'name');

        $institutions = $instututionsModel->findWhere(['status' => 'activo']);
        $institutions = ModelToUIHelper::make()->formatDataForSelect($institutions, 'id', 'nombre');
        $institutions = $this->addRow($institutions);
        $locations = $locationsModel->findWhere(['status' => 'activo']);
        $locations = ModelToUIHelper::make()->formatDataForSelect($locations, 'id', 'nombre');
        $locations = $this->addRow($locations);
        $args = [
            'status' => $projectsModel::STATUS,
            'institutions' => $institutions,
            'locations' => $locations,
            // 'pricing_plans' => $pricingPlansFormattedForUi
        ];
        $session = $request->session();
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addActive('Crear Evento');
        $contentView = new View('admin.proyectos.crear', [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UIFormsProyectoService::make()->getCreateEventForm($args),
            'linkCancelBtn' => APP_DIRECTORY_PATH . '/lista-de-eventos',
        ]);
        $template = $this->create($session, $breadCrumb, $contentView);
        $this->response->view($template)->send();
    }
}
