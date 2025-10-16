<?php

namespace app\core\http;

class Redirect
{
    private $url;
    private Response $response;
    public function __construct(Response $reponse)
    {
        $this->url = null;
        $this->response = $reponse;
    }

    public function route(...$args)
    {
        $numArgs = count($args);
        if ($numArgs === 1 && is_string($args[0])) {
            $this->url = $args[0];
        } else if ($numArgs === 2) {
            $route = $args[0];
            $params = $args[1];
            $this->url = $this->url($route, $params);
        }

        $this->response->header('Location', $this->url);
        $this->response->status(Response::HTTP_FOUND);
        $this->response->send();
    }

    public function url(string $route, array $params = [])
    {
        $encodedParams = array_map('urlencode', $params);
        $encodedParams = array_map(function ($key, $value) {
            return urlencode($key) . '/' . urlencode($value);
        }, array_keys($encodedParams), $encodedParams);

        $url = $route . implode('/', $encodedParams);

        return $url;
    }

    public function action(array $callback, array $params = [])
    {
        // Verificar que el array tenga al menos una entrada
        if (empty($callback)) {
            echo "Error: El array de callback está vacío.";
            return;
        }

        // Extraer la clase y el método del array
        $class = array_keys($callback)[0];
        $method = array_values($callback)[0];

        // Verificar que se hayan extraído la clase y el método
        if (!$class || !$method) {
            echo "Error: El array de callback no tiene la estructura esperada.";
            return;
        }

        // Verificar si la clase existe
        if (!class_exists($class)) {
            echo "Error: La clase $class no existe.";
            return;
        }

        // Verificar si el método existe en la clase
        if (!method_exists($class, $method)) {
            echo "Error: El método $method no existe en la clase $class.";
            return;
        }

        // Crear una instancia de la clase
        $controller = new $class();

        // Llamar al método con los parámetros proporcionados
        $controller->$method(...$params);
    }



    // public function action(array $callback, array $params = [])
    // {
    //     // echo '<pre>';
    //     // var_dump($callback);
    //     // echo '</pre>';

    //     $class = array_keys($callback)[0];
    //     $method = array_values($callback)[0];
    //     $controller = new $class();
    //     $controller->$method(...$params);



    //     // $controller = new $class();

    //     // return call_user_func_array([$controller, $method], $params);

    //     // return call_user_func_array($controller, $params);
    // }
}
