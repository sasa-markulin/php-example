<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controllerAction)
    {
        $this->routes[strtoupper($method)][$uri] = $controllerAction;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes[$method] as $route => $controllerAction) {
            $routePattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $route); 
            if (preg_match("#^{$routePattern}$#", $uri, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('@', $controllerAction);
                $this->callAction($controller, $action, $matches);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    protected function getUri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Get the base path from the environment variable
        $basePath = rtrim($_ENV['BASE_PATH'], '/');

        // Remove the base path from the URI if it exists
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Return the cleaned URI
        return rtrim($uri, '/') ?: '/';
    }

    protected function callAction($controller, $action, $params = [])
    {
        $controller = "App\\Controllers\\{$controller}";
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found.");
        }

        $controllerInstance = new $controller;
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("Method {$action} not found in controller {$controller}.");
        }
        
        if (!empty($params)) {
            call_user_func_array([$controllerInstance, $action], $params);
        } else {
            $controllerInstance->$action();
        }
    }
}
