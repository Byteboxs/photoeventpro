<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IRunnable;
use app\core\Application;
use app\services\admin\proyectos\model\EventoVendedoresService;

class ActionEventoVendedoresSave implements IRunnable
{
    private $response;
    private $args;
    private array $formData;
    private EventoVendedoresService $service;
    private $event_id = null;
    private array $vendedores = [];
    public function __construct(...$args)
    {
        $this->response = Application::$app->response;
        $this->service = new EventoVendedoresService();
        $this->args = $args;
        $this->formData = $this->args['request']->getData();
    }
    public function run(...$args)
    {
        foreach ($this->formData as $key => $value) {
            if ($key === 'event_id') {
                $this->event_id = (int)$value;
            } else {
                $this->vendedores[] = (int)$value;
            }
        }

        $data = [
            'event_id' => $this->event_id,
            'vendedores' => $this->vendedores
        ];
        $result = $this->service->eventoVendedoresSave($data);
        if ($result === null) {
            $status = [
                'status' => 'fail',
                'message' => 'Error general, contacte al administrador.'
            ];
            $this->response->json($status)->send();
        } else {
            $this->response->json($result)->send();
        }
    }
}
