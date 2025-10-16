<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IRunnable;
use app\core\Application;
use app\services\imageuploader\GDThumbnailGenerator;
use app\services\imageuploader\ImageUploader;
use app\services\ProjectsService;

class ActionSubirImagenesClientesController implements IRunnable
{
    private $response;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    public function run(...$args)
    {
        // echo '<pre>';
        // var_dump($args);
        // echo '</pre>';
        $data = [
            'proyecto_id' => $args['proyecto_id'],
            'cliente_id' => $args['cliente_id'],
            'images' => $_FILES['images']
        ];
        $base_path = Application::$app->config->get('base_path');
        $upload_dir = Application::$app->config->get('upload_dir');
        $uploadDir = $base_path . APP_DIRECTORY_PATH . '/' . $upload_dir . '/eventos/';
        $uploadPathUrl = $uploadDir . "{$args['proyecto_id']}/{$args['cliente_id']}/";

        $uploader = new ImageUploader($uploadPathUrl, new GDThumbnailGenerator());
        $result = $uploader->upload($data);

        $data = [
            'proyecto_id' => $args['proyecto_id'],
            'cliente_id' => $args['cliente_id'],
            'file_upload_result' => $result,
            'upload_path_url' => $uploadPathUrl
        ];

        $result = ProjectsService::make()->loadClientImages($data);
        $this->response->json($result)->send();
    }
}
