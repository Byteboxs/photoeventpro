<?php

namespace app\controllers\auth;

use app\core\Application;
use app\core\http\Request;
use app\core\views\View;
use app\services\ui\form\Bootstrap5FormRenderer;
use app\services\ui\form\UILoginFormService;

class UIAuthController
{
    protected $response;
    protected $logger;
    protected $tplPath;
    protected $tplFolder;
    protected $tplView;
    protected $imgPath;
    protected $publicPath;
    public function __construct()
    {
        $this->logger = Application::$app->logger;
        $this->response = Application::$app->response;
        $this->tplFolder = 'auth';
        $this->publicPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public';
        $this->tplPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/template/' . $this->tplFolder;
        $this->imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
        $this->tplView = 'template.auth.tpl';
    }

    public function iniciarSesion(Request $request)
    {
        $content = new View('auth.login', [
            'renderer' => new Bootstrap5FormRenderer(),
            'form' => UILoginFormService::make()->getForm(),
        ]);

        $template = new View($this->tplView, [
            'publicPath' => $this->publicPath,
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'title' => 'Iniciar Sesion',
            'description' => 'Accede a tu panel de control personalizado para organizar tus sesiones fotográficas, gestionar tus clientes y realizar un seguimiento de tus finanzas.',
            'h1' => 'Acceda a su cuenta',
            'loginDescription' => 'Acceda a su cuenta',
        ]);
        $template->with('content', $content);
        $this->response->view($template)->send();
    }
}
