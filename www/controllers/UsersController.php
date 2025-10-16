<?php

namespace app\controllers;

use app\core\http\Request;
use app\core\Application;
use app\core\exceptions\ModelNotFoundException;
use app\core\SecureSession;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\User;
use app\services\AuthService;

class UsersController
{
    private $appFolderAddress;
    private $response;
    private $sessionName;

    public function __construct()
    {
        $this->appFolderAddress = APP_DIRECTORY_PATH;
        $this->response = Application::$app->response;
        $this->sessionName = 'photoApp';
    }

    public function loginUserInterface(Request $request)
    {
        $view = new View('signin');
        $view->with('title', 'Entrar');
        $this->response->view($view)->send();
    }

    public function loginUser(...$parametros)
    {
        // $model = new User();
        $request = $parametros['request'];
        $data = $request->getData();
        $email = $data['email'];
        $password = $data['password'];

        try {
            $authService = new AuthService(new User());
            $verify = $authService->authenticate($email, $password);
            if ($verify) {
                $authService->authorize();
                $session = $request->session();
                $rol = strtolower($session->get('userRole'));
                $this->response->json(['status' => 'success', 'url' => $this->appFolderAddress . '/dashboard-' . $rol, "message" => 'Iniciando sesion...'])->send();
            } else {
                $this->response->json(['status' => 'fail', "message" => 'Oops Parece que tus datos no son correctos.'])->send();
            }
        } catch (ModelNotFoundException $exception) {
            $this->response->json(['status' => 'fail', "message" => 'El email no existe'])->send();
        } catch (\Exception $exception) {
            $this->response->json(['status' => 'fail', "message" => 'Oops Parece que hubo un error.'])->send();
        }
    }

    public function logout(Request $request)
    {
        $secureSession = SecureSession::make($this->sessionName)->start();
        $secureSession->destroy();
        $this->response->json(['status' => 'success', 'url' => $this->appFolderAddress . '/iniciar-sesion', "message" => 'Cerrando sesion...'])->send();
    }
}
