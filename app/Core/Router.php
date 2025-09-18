<?php
namespace App\Core;

class Router {
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Normaliza la URI para entornos locales con subcarpetas
        $base = '/Alcaldia';
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
            if ($uri === '' || $uri === false) $uri = '/';
        }
        $routes = require __DIR__ . '/../../routes/web.php';
        if (isset($routes[$method][$uri])) {
            [$controller, $action] = $routes[$method][$uri];
            (new $controller)->$action();
        } else {
            http_response_code(404);
            echo 'Página no encontrada';
        }
    }
}
