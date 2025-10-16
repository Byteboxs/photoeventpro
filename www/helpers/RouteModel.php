<?php

namespace app\helpers;

class RouteModel
{
    private $type;
    private $route;
    private $url;
    private $controller;
    private $method;
    private $isRule;
    private $rules;

    public function __construct($type, $route, $url, $controller, $method, $isRule, array $rules)
    {
        $this->type = $type;
        $this->route = $route;
        $this->url = $url;
        $this->controller = $controller;
        $this->method = $method;
        $this->isRule = $isRule;
        $this->rules = $rules;
    }

    // seters
    public function setType($type)
    {
        $this->type = $type;
    }
    public function setRoute($route)
    {
        $this->route = $route;
    }
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function setController($controller)
    {
        $this->controller = $controller;
    }
    public function setMethod($method)
    {
        $this->method = $method;
    }
    public function setIsRule($isRule)
    {
        $this->isRule = $isRule;
    }
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    // geters
    public function getType()
    {
        return $this->type;
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function getUrl(array $params = [])
    {
        $url = $this->url;
        foreach ($params as $key => $value) {
            $url = str_replace("{" . $key . "}", $value, $url);
        }
        return $url;
    }
    public function getController()
    {
        return $this->controller;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getIsRule()
    {
        return $this->isRule;
    }
    public function getRules()
    {
        return $this->rules;
    }

    public function getControllerMethodArray()
    {
        return [$this->controller, $this->method];
    }
}
