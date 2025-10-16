<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\services\ProjectsService;

class ActionRealizarPagoEfectivoController implements IRunnable
{
    private $response;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    public function run(...$args)
    {
        $result = ProjectsService::make()->makeCashPayment($args);
        $this->response->json($result)->send();
    }
}
