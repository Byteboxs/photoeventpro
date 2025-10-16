<?php

namespace app\core\routes;

use app\controllers\BaseController;
use app\core\Application;
use app\core\http\Request;
use app\core\exceptions\MethodNameNotFoundException;
use app\core\exceptions\NotFoundException;
use app\core\exceptions\HttpMethodNotAllowedException;
use app\core\collections\ArrayList;
use app\core\routes\RouteConf;

class Route
{
    public  $routes = [];
    private $currentGroup = [];

    private $dependencias = [];
    private $logger;

    public function __construct()
    {
        $this->routes = [
            'GET' => [],
            'POST' => [],
        ];
        $this->logger = Application::$app->logger;
    }

    public function get($uri, array $action)
    {
        $request = new Request();
        $uri = Application::$SERVER_FOLDER . $uri;
        return $this->addRoute($uri, $action);
    }

    public function post($uri, array $action)
    {
        $uri = Application::$SERVER_FOLDER . $uri;
        return $this->addRoute($uri, $action, 'POST');
    }

    private function addRoute($uri, array $action, $method = 'GET')
    {
        $routeConf = new RouteConf($uri);
        $this->routes[$method][$uri] = (object)['action' => $action, 'routeConf' => $routeConf];
        return $routeConf;
    }

    public function hasRoute(string $uri, string $patternUri): ?array
    {
        $pattern = str_replace('/', '\/', $patternUri);
        $pattern = '/^' . preg_replace('/\{(\w+)(\?)?\}/', '(?<$1>[^\/]+)?', $pattern) . '$/';
        return preg_match($pattern, $uri, $matches) ? $matches : null;
    }

    // public function findUri($uri, $routes)
    // {
    //     foreach ($routes as $patternUri => $route) {
    //         $hasRoute = $this->hasRoute($uri, $patternUri);
    //         if ($hasRoute) {
    //             $route->matches = $hasRoute;
    //             return $route;
    //         }
    //     }
    //     return null;
    // }
    public function findUri($uri, $routes)
    {
        $uriPath = parse_url($uri, PHP_URL_PATH); // Extract the path from the URI
        foreach ($routes as $patternUri => $route) {
            $hasRoute = $this->hasRoute($uriPath, $patternUri); // Match only the path
            if ($hasRoute) {
                $route->matches = $hasRoute;
                return $route;
            }
        }
        return null;
    }

    // public function getRoute($uri, $routes)
    // {
    //     foreach ($routes as $patternUri => $route) {
    //         if ($this->hasRoute($uri, $patternUri)) {
    //             return $patternUri;
    //         }
    //     }
    //     return null;
    // }

    public function getRoute($uri, $routes)
    {
        $uriPath = parse_url($uri, PHP_URL_PATH); // Extract the path from the URI
        foreach ($routes as $patternUri => $route) {
            if ($this->hasRoute($uriPath, $patternUri)) {
                return $patternUri;
            }
        }
        return null;
    }

    private function action($route)
    {
        $params = $route->params;
        $params['request'] = new Request();
        $action = $route->action;
        $conf = $route->routeConf;

        if (count($conf->getRules()) > 0) {
            foreach ($conf->getRules() as $field => $rules) {
                if (!preg_match($rules, $params[$field])) {
                    throw new NotFoundException('');
                }
            }
        }
        try {
            return $this->callAction($action, $params);
        } catch (NotFoundException $e) {
            $responseType = 'HTML';
            if (isset($route->group['prefix']) && $route->group['prefix'] === '/api') {
                $responseType = 'JSON';
            }
            (new BaseController())->handleNotFound($responseType);
            $this->logger->log('error', $e->getMessage());
        } catch (HttpMethodNotAllowedException $e) {
            $responseType = 'HTML';
            (new BaseController())->handleHttpMethodNotAllowed($responseType);
            $this->logger->log('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            $responseType = 'HTML';
            (new BaseController())->handleInvalidArgumentException($e);
            $this->logger->log('error', $e->getMessage());
        } catch (MethodNameNotFoundException $e) {
            $responseType = 'HTML';
            (new BaseController())->handleMethodNotFoundException($e->getMessage());
            $this->logger->log('error', $e->getMessage());
        }
    }

    private function callAction($action, $params)
    {
        $method = $action[1];
        if (is_array($action)) {
            $className = is_object($action[0]) ? get_class($action[0]) : $action[0];
            if (class_exists($className) && method_exists($className, $action[1])) {

                $instance = new $className();
                $method = new \ReflectionMethod($instance, $action[1]);
                return $method->invokeArgs($instance, $params);
            } else {
                throw new MethodNameNotFoundException($method);
            }
        }

        if (is_callable($action)) {
            $function = new \ReflectionFunction($action);
            if ($function->getNumberOfParameters() === 0) {
                return $function->invoke();
            }
            return $function->invokeArgs($params);
        }
        throw new \InvalidArgumentException('Acción no válida');
    }

    public function handle($request, $middlewares = [])
    {
        $stack = function ($request) {
            return $this->action($request);
        };
        if (is_array($middlewares) && count($middlewares) > 0) {
            $middlewares = array_reverse($middlewares);
            foreach ($middlewares as $middleware) {
                $midd = new $middleware();
                $stack = function ($request) use ($midd, $stack) {
                    return $midd->handle($request, $stack);
                };
            }
        }
        return $stack($request);
    }

    public function dispatch(Request $request)
    {
        $uri = $request->url();
        $newUri = $request->removeAppDirectoryFromUri($uri, APP_DIRECTORY);
        $params = null;
        $method = $request->method();

        try {
            if (isset($this->routes[$method]) && count($this->routes[$method]) > 0) {
                $routes = $this->routes[$method];
                $route = $this->findUri($newUri, $routes);
                if ($route && $route->matches != null) {
                    if ($method == 'GET') {
                        $params = array_filter($route->matches, 'is_string', ARRAY_FILTER_USE_KEY);
                        unset($_GET['url']);
                        $_GET = array_merge($_GET, $params);
                        $route->params = $params;
                    } else if ($method == 'POST') {
                        $route->params = $_POST;
                    }
                    $kernel = Application::$app->kernel;
                    $url = $this->getRoute($newUri, $routes);
                    $middelwares = $kernel->getRouteMiddelwares($url)
                        ->merge($kernel->getGroupMiddelwares($url))
                        ->merge($kernel->getGlobalMiddlewares());
                    $this->handle($route, $middelwares->toArray());
                } else {
                    throw new NotFoundException($newUri);
                }
            } else {
                throw new HttpMethodNotAllowedException();
            }
        } catch (NotFoundException $e) {
            $controller = new BaseController();
            $controller->handleNotFound($this->getResponseType($route, $request));
            $this->logger->log('error', $e->getMessage());
        } catch (HttpMethodNotAllowedException $e) {
            $controller = new BaseController();
            $controller->handleNotFound($this->getResponseType($route, $request));
        }
    }

    private function getResponseType($route, Request $request)
    {
        $responseType = 'HTML';
        if (isset($route->group['prefix']) && $route->group['prefix'] === '/api' || preg_match('/\/api\//', $request->fullUrl())) {
            $responseType = 'JSON';
        }
        return $responseType;
    }

    public function group(\Closure $callback, array $options = [])
    {
        $kernel = Application::$app->kernel;
        $prefix = $options['prefix'] ?? '';
        $middleware = $options['middleware'] ?? [];

        $this->currentGroup = [
            'prefix' => $prefix,
            'middleware' => $middleware,
        ];

        $currentRoutes = $this->routes;
        $this->routes = [
            'GET' => [],
            'POST' => [],
        ];
        $callback($this);

        $groupedRoutes = $this->routes;
        $this->routes = $currentRoutes;

        foreach (['GET', 'POST'] as $method) {
            foreach ($groupedRoutes[$method] as $uri => $route) {
                $fullUri = $prefix . $uri;
                $this->routes[$method][$fullUri] = (object)[
                    'action' => $route->action,
                    'routeConf' => $route->routeConf,
                    'group' => $this->currentGroup,

                ];
                $middlewares = new ArrayList();
                $middlewares->addArray($middleware);
                $kernel->addMiddelwaresToGroup($fullUri, $middlewares);
            }
        }
    }
}
