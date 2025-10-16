<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\models\Project;
use app\services\strategies\ProjectsStrategy;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\paginator\Paginator;
use app\services\UIProjectsTableService;

class UIEventosController extends UISneatTemplateController implements IDrawable
{
    private $contentView;
    private $table;
    private $paginator;
    public function __construct()
    {
        parent::__construct();
    }

    private function initTable($page, $config)
    {
        $this->paginator = new Paginator(
            $page,
            APP_DIRECTORY_PATH . '/lista-de-eventos/page/',
            $config['currentPage'],
            $config['totalPages'],
            $config['perPage'],
            $config['totalData'],
        );
        $this->table = UIProjectsTableService::make(
            $config['data'],
            new ProjectsStrategy(),
            'project_id',
            'tableProjects',
            [
                'tplPath' => $this->tplPath,
                'linkDetalleProyecto' => APP_DIRECTORY_PATH . '/evento/detalle/',
                'linkRegistrarCliente' => APP_DIRECTORY_PATH . '/registrar-cliente-evento/',
                'linkEditarProyecto' => APP_DIRECTORY_PATH . '/editar-proyecto/',
                'linkVerProyecto' => APP_DIRECTORY_PATH . '/evento/detalle/',
                'linkPosEfectivo' => APP_DIRECTORY_PATH . '/evento/pos/efectivo/',
            ]
        )->get();
    }

    private function init($page)
    {
        $model = new Project();
        $result = $model->getProjects($page);
        $this->initTable($page, $result);
        $this->contentView = new View('admin.proyectos.listado', [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'table' => $this->table,
            'appPath' => APP_DIRECTORY_PATH,
            'paginator' => $this->paginator
        ]);
    }

    public function draw(...$args)
    {
        $request = $args["request"];
        $session = $request->session();
        $data = $request->getData();
        $page = $data['page'] ?? 1;
        $this->init($page);
        $breadCrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addActive('Eventos');
        $template = $this->create($session, $breadCrumb, $this->contentView);
        $this->response->view($template)->send();
    }
}
