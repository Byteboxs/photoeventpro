<?php

namespace app\controllers\admin\productos;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\services\ProductsService;

class ActionEditarProductoController implements IRunnable
{
    private $response;
    private $data;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    private function sendResult($result, ...$args)
    {
        if ($result) {
            $this->response->json([
                'status' => 'success',
                'url' => RouteHelper::getAppFolderAddress() . '/producto/detalle/' . $args['id'],
                "message" => 'Producto editado exitosamente.'
            ])->send();
        } else {
            $this->response->json([
                'status' => 'fail',
                "message" => 'Oops Parece que hubo un error.'
            ])->send();
        }
    }
    public function run(...$args)
    {
        $this->data = $args['request']->getData();
        // var_dump($this->data);
        $result = ProductsService::make()->update($this->data);
        $this->sendResult($result, ...$this->data);
    }
}
