<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function resolve(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');

        if (isset($this->routes[$requestMethod][$url])) {
            $route = $this->routes[$requestMethod][$url];
            $controllerClass = $route['controller'];
            $method = $route['method'];

            $controller = new $controllerClass();
            $controller->$method();
            return;
        }

        http_response_code(404);
        echo "Page non trouvée";
    }
}
