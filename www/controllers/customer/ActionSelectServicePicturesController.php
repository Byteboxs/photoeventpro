<?php

namespace app\controllers\customer;

use app\controllers\IRunnable;
use app\core\Application;
use app\services\ProjectsService;

class ActionSelectServicePicturesController implements IRunnable
{
    private $response;
    private $images;
    private $order_detail_id;

    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    public function run(...$args)
    {
        $this->order_detail_id = $args['order_detail_id'];
        $this->images = json_decode($args['images']);
        $service = ProjectsService::make();
        $result = $service->saveSelectedImages($this->order_detail_id, $this->images);
        $this->response->json($result)->send();
    }
}
