<?php
// Punto de entrada único para la aplicación MVC
require_once __DIR__ . '/app/Core/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/routes/web.php';

use App\Core\Router;

$router = new Router();
$router->dispatch();
