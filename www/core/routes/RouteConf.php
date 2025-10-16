<?php

namespace app\core\routes;

use app\core\Application;
use app\core\collections\ArrayList;
use app\core\collections\Map;

class RouteConf
{
    private Map $rules;
    private string $route;

    public function __construct(string $route)
    {
        $this->route = $route;
        $this->rules = new Map();
    }

    public function where(...$params)
    {
        $numParams = count($params);
        if ($numParams === 1 && is_array($params[0])) {
            $this->rules->addArray($params[0]);
        } else if ($numParams === 2) {
            $this->rules->add($params[0], $params[1]);
        } else {
            throw new \InvalidArgumentException("The RouteConf::where method accepts 1 or 2 arguments.");
        }
        return $this;
    }

    public function getRules()
    {
        return $this->rules->toArray();
    }

    public function middelwares($array)
    {
        $kernel = Application::$app->kernel;
        $middlewares = new ArrayList();
        $middlewares->addArray($array);
        $kernel->addMiddlewaresToRoute(
            $this->route,
            $middlewares
        );
        return $this;
    }
}
