<?php

namespace app\controllers\admin\vendedores;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\services\admin\vendedores\model\VendedoresService;

class ActionVendedorUpdate implements IRunnable
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
        // var_dump($this->formData);
        $result = $this->service->editarVendedor($this->formData, RouteHelper::getUrlFor('vendedoresView'));
        if ($result === null) {
            $status = [
                'status' => 'fail',
                'message' => 'No se puede editar al vendedor, compruebe los datos. Si el problema persiste, contacte al administrador.'
            ];
            $this->response->json($status)->send();
        } else {
            $this->response->json($result)->send();
        }
    }
}
