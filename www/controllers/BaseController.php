<?php

namespace app\controllers;

use app\core\Application;
use app\core\views\View;
use app\core\http\Response;

class BaseController
{
    private $response;
    private $template404;
    private $tplPath;
    private $tplFolder;
    private $home;
    private $error500;

    public function __construct()
    {
        $this->response = Application::$app->response;
        // $this->template404 = 'errors.400.default.404';
        $this->template404 = 'errors.400.404';
        $this->tplFolder = 'sneat';
        $this->tplPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/template/' . $this->tplFolder;
        $this->home = APP_DIRECTORY_PATH . '/';
        $this->error500 = 'errors.500.default.500';
    }
    function handleNotFound($responseType)
    {
        if ($responseType === 'JSON') {
            // Response::json([
            //     'error' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            // ], Response::HTTP_NOT_FOUND)->send();
            $this->response->json([
                'error' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    'details' => '',
                ],
            ], Response::HTTP_NOT_FOUND)->send();
        } else {
            $view = new View($this->template404, [
                'tplPath' => $this->tplPath,
                'home' => $this->home,
                'titulo' => 'Página perdida',
                'mensaje' => 'Oops! 😖 La URL solicitada no se ha encontrado en este servidor.',
                'buttonTitle' => 'Sacame de aqui',
            ]);
            $view->with('title', 'Critical error');
            $view->with('message', 'No se encuentra el enlace');
            $view->with('action1', 'Ejemplo');
            $view->with('action2', 'Ejemplo');
            $view->with('bye', 'Ejemplo');
            $this->response->view($view, Response::HTTP_NOT_FOUND)->send();
        }
    }

    function handleHttpMethodNotAllowed($responseType)
    {
        if ($responseType === 'JSON') {
            $this->response->json([
                'error' => [
                    'code' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'message' => Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED],
                    'details' => 'handleHttpMethodNotAllowed',
                ],
            ], Response::HTTP_METHOD_NOT_ALLOWED)->send();
        } else {
            echo 'handleMethodNotAllowed';
        }
    }

    function handleError($e)
    {
        $view = new View($this->error500);
        $view->with('title', 'Oops!');
        $view->with('error', 'handleDbMustNoAccessedBeforeInitialization<br>' . $e->getMessage());
        $this->response->view($view, Response::HTTP_INTERNAL_SERVER_ERROR)->send();
    }

    function handleInvalidArgumentException($e)
    {
        $view = new View($this->error500);
        $view->with('title', 'Oops!');
        $view->with('error', 'handleInvalidArgumentException<br>' . $e->getMessage());
        $this->response->view($view, Response::HTTP_INTERNAL_SERVER_ERROR)->send();
    }
    function handleInvalidViewException($e)
    {
        $view = new View($this->error500);
        $view->with('title', 'Oops!');
        $view->with('error', 'handleInvalidViewException<br>' . $e->getMessage());
        $this->response->view($view, Response::HTTP_INTERNAL_SERVER_ERROR)->send();
    }
    public function handleMethodNotFoundException($msg)
    {
        $view = new View($this->error500);
        $view->with('title', 'Oops!');
        $view->with('error', $msg);
        $this->response->view($view, Response::HTTP_INTERNAL_SERVER_ERROR)->send();
    }
}
