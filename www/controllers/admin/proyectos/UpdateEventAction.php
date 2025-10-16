<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\services\admin\proyectos\model\EventoService;

class UpdateEventAction implements IRunnable
{
    private $response;
    private $args;
    private array $formData;
    private EventoService $service;
    private $successUrl;
    private $routes;
    public function __construct(...$args)
    {
        $this->response = Application::$app->response;
        $this->service = new EventoService();
        $this->args = $args;
        $this->formData = $this->args['request']->getData();
        $this->routes = new RouteHelper();
        $this->successUrl = $this->routes->getUrlFor(
            'eventoDetalleView',
            ['proyecto' => $this->formData['evento_id']]
        );
    }
    public function run(...$args)
    {
        $result = $this->service->updateEvent($this->formData, $this->successUrl);
        if ($result === null) {
            $status = [
                'status' => 'fail',
                'message' => 'No se puede agregar al vendedor, compruebe los datos. Si el problema persiste, contacte al administrador.'
            ];
            $this->response->json($status)->send();
        } else {
            $this->response->json($result)->send();
        }
    }
}
