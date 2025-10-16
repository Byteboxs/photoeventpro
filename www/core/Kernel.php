<?php

namespace app\core;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Kernel
{
    private ArrayList $middlewareGlobal;
    private Map $routeMiddleware;
    private Map $middelwareGroups;

    public function __construct()
    {
        $this->middlewareGlobal = new ArrayList();
        $this->routeMiddleware = new Map();
        $this->middelwareGroups = new Map();
        $this->register();
    }

    public function register(): void
    {
    }

    public function addMiddlewareGlobal(String $middleware): void
    {
        $this->middlewareGlobal->add($middleware);
    }

    public function addMiddlewareRoute(string $route, String $middleware): void
    {
        $middlewares = $this->routeMiddleware->getOrDefault($route, new ArrayList());
        $middlewares->add($middleware);
        $this->routeMiddleware->put($route, $middlewares);
    }

    public function addMiddlewaresToRoute(string $route, ArrayList $middlewares): void
    {
        $this->routeMiddleware->put($route, $middlewares);
    }

    public function addMiddelwareGroup(string $group, string $middleware): void
    {
        $groups = $this->middelwareGroups->getOrDefault($group, new ArrayList());
        $groups->add($middleware);
        $this->middelwareGroups->put($group, $groups);
    }

    public function addMiddelwaresToGroup(string $group, ArrayList $middlewares): void
    {
        $this->middelwareGroups->put($group, $middlewares);
    }

    public function getGlobalMiddlewares(): ArrayList
    {
        return $this->middlewareGlobal;
    }

    public function getRouteMiddelwares(string $route): ArrayList
    {
        if (!$this->routeMiddleware->contains($route)) {
            return new ArrayList();
        }
        return $this->routeMiddleware->get($route);
    }

    public function getGroupMiddelwares(string $group): ArrayList
    {
        if (!$this->middelwareGroups->contains($group)) {
            return new ArrayList();
        }
        return $this->middelwareGroups->get($group);
    }
}
