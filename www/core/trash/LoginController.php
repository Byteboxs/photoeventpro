<?php

// Mostrar todos los errores


namespace app\controllers;

// error_reporting(E_ALL);
// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);

use app\core\Application;
use app\core\collections\ArrayList;
use app\core\collections\Comparable;
use app\core\collections\LinkedBag;
use app\core\http\Request;
use app\core\model\Validator;
use app\core\views\View;
use app\core\http\Response;
use app\core\Str;
use app\models\Persona;
use app\models\Usuario;
use core\trash\Builder;

class LoginController
{
    // private Request $request;

    public function __construct() {}

    public function login(Request $request)
    {
        // $response = Application::$app->response;
        // $view = new View('login');
        // $view->with('title', 'Login');
        // $response->view($view)->send();
    }

    public function form(Request $request)
    {
        $response = Application::$app->response;
        $view = new View('login.form');
        $response->view($view)->send();
    }
}
