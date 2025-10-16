<?php

namespace app\controllers\template;

use app\core\Application;
use app\core\Session;
use app\core\views\View;
use app\models\User;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\menu\Menu;
use app\services\UIMenuService;

abstract class UISneatTemplateController
{
    protected $response;
    protected $logger;
    protected $tplPath;
    protected $tplFolder;
    protected $tplView;
    protected $footerView;
    protected $menuView;
    protected $navbarView;
    protected $imgPath;

    public function __construct()
    {
        $this->logger = Application::$app->logger;
        $this->response = Application::$app->response;
        $this->tplFolder = 'sneat';
        $this->tplPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/template/' . $this->tplFolder;
        $this->imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
        $this->tplView = 'template.sneat.tpl';
        $this->footerView = 'template.sneat.footer';
        $this->menuView = 'template.sneat.menu';
        $this->navbarView = 'template.sneat.navbar';
    }

    public function create(Session $session, BreadcrumbBuilderService $breadCrumb, View $content)
    {
        $model = new User();
        $usuario = $model->find($session->get('userId'));


        $rol = $session->get('userRole');

        $nombre = '';

        if ($rol === 'Administrador') {
            $nombre = $usuario->getEmploye()->nombre_completo;
        } else if ($rol === 'Vendedor') {
            $nombre = $usuario->getEmploye()->nombre_completo;
        } else {
            $nombre = $usuario->getCustomer()->nombre_completo;
        }

        $menu = UIMenuService::make($rol)->build();

        $footer = new View($this->footerView, []);
        $menuView = new View($this->menuView, [
            'menu' => $menu,
        ]);
        $navBar = new View($this->navbarView, [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
            'avatar' => 'avatar_robot.png',
            'nombre' => $nombre,
            'rol' => $rol,
        ]);

        $template = new View($this->tplView, [
            'tplPath' => $this->tplPath,
            'imgPath' => $this->imgPath,
        ]);

        $template->with('menu', $menuView);
        $template->with('navbar', $navBar);
        $template->with('breadcrumb', $breadCrumb->build());
        $template->with('content', $content);
        $template->with('footer', $footer);

        return $template;
    }
}
