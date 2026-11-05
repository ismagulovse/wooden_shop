<?php

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = '/' . trim($uri, '/');
        if ($path === '//') {
            $path = '/';
        }

        $candidates = $this->routes[$method] ?? [];

        foreach ($candidates as $route => $handler) {
            $pattern = '#^' . preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '([^/]+)', $route) . '$#';
            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            array_shift($matches);
            [$controller, $action] = $handler;
            $instance = new $controller();
            call_user_func_array([$instance, $action], $matches);
            return;
        }

        http_response_code(404);
        (new ErrorController())->notFound();
    }
}

