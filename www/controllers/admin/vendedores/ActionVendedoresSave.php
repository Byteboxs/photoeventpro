<?php

namespace app\controllers\admin\vendedores;

use app\controllers\IRunnable;
use app\core\Application;
use app\services\admin\vendedores\model\VendedoresService;

class ActionVendedoresSave implements IRunnable
{
    private $response;
    private $args;
    private array $formData;
    private VendedoresService $service;
    public function __construct(...$args)
    {
        $this->response = Application::$app->response;
        $this->service = new VendedoresService();
        $this->args = $args;
        $this->formData = $this->args['request']->getData();
    }
    public function run(...$args)
    {
        $result = $this->service->registrarVendedor($this->formData);
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
