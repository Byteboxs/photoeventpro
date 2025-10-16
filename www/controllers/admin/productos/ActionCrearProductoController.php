<?php

namespace app\controllers\admin\productos;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\services\ProductsService;

class ActionCrearProductoController implements IRunnable
{
    private $response;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    private function sendResult($result)
    {
        if ($result) {
            $this->response->json([
                'status' => 'success',
                'url' => APP_DIRECTORY_PATH . '/listado-de-productos',
                "message" => 'Producto creado exitosamente.'
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
        $base_path = Application::$app->config->get('base_path');
        $upload_dir = Application::$app->config->get('upload_dir');
        $uploadDir = $base_path . APP_DIRECTORY_PATH . '/' . $upload_dir . '/servicios';
        $data = [
            'form' => $args['request']->getData(),
            'uploadPath' => $uploadDir,
            'files' => $_FILES
        ];

        $result = ProductsService::make()->create($data);
        $this->response->json($result)->send();
    }
}
