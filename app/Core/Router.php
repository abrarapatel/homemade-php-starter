<?php
// app/core/Router.php

namespace App\Core;

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = require __DIR__ . '/../config/routes.php';
    }

    public function resolve()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = explode('?', $requestUri)[0];

        $scriptName = $_SERVER['SCRIPT_NAME'];
        // Determine the base path (e.g., /minixo)
        $basePath = str_replace(['/public_html/index.php', '/public/index.php', '/index.php'], '', $scriptName);

        $path = substr($requestUri, strlen($basePath));

        // If the path still starts with /public_html, strip it
        // This handles cases where the user explicitly types /public_html/
        if (strpos($path, '/public_html/') === 0) {
            $path = substr($path, 12);
        } elseif ($path === '/public_html') {
            $path = '/';
        } elseif (strpos($path, '/public/') === 0) {
            $path = substr($path, 7);
        } elseif ($path === '/public') {
            $path = '/';
        }

        if (empty($path)) {
            $path = '/';
        }

        // Check if it's an API route
        if (strpos($path, '/api/') === 0) {
            return $this->handleRoute($path, $this->routes['api'] ?? [], true);
        }

        // Handle page route
        return $this->handleRoute($path, $this->routes['pages'] ?? [], false);
    }

    private function handleRoute($path, $routes, $isApi)
    {
        // 1. Try Exact Match
        if (isset($routes[$path])) {
            $executed = false;
            $result = $this->dispatch($routes[$path], [], $executed);
            if ($executed) {
                return $result;
            }
        }

        // 2. Try Regex Match for Params
        foreach ($routes as $routePattern => $handler) {
            if (strpos($routePattern, '{') !== false) {
                // Escape special chars
                $regex = preg_quote($routePattern, '@');

                // Convert \{param\} to capture group ([^/]+)
                $regexPattern = preg_replace('/\\\{[a-zA-Z0-9_]+\\\}/', '([^/]+)', $regex);

                $regexPattern = '@^' . $regexPattern . '$@';

                if (preg_match($regexPattern, $path, $matches)) {
                    array_shift($matches);
                    $executed = false;
                    $result = $this->dispatch($handler, $matches, $executed);
                    if ($executed) {
                        return $result;
                    }
                }
            }
        }

        // 404
        if ($isApi) {
            Response::json(['success' => false, 'message' => 'API Not Found'], 404);
        } else {
            $this->render404();
        }
    }

    private function dispatch($handler, $params, &$executed)
    {
        $parts = explode('@', $handler);
        $controllerName = "App\\Controllers\\" . $parts[0];
        $method = $parts[1];

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $method)) {
                $executed = true;
                return call_user_func_array([$controller, $method], $params);
            }
        }
        $executed = false;
        return null;
    }

    private function render404()
    {
        http_response_code(404);
        require __DIR__ . '/../views/404.php';
        exit;
    }
}
