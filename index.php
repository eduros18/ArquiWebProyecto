<?php
/**
 * PLATAFORMA EDITORIAL MULTICANAL MVC
 * Universidad Mesoamericana (UMES) - Sede Quetzaltenango
 * Front Controller & Router Principal
 */

require_once __DIR__ . '/config/config.php';

// Mapeo seguro de Controladores para evitar carga arbitraria de archivos
$controllerParam = $_GET['c'] ?? 'home';
$actionParam = $_GET['a'] ?? 'index';

$routes = [
    'home'  => 'HomeController',
    'book'  => 'BookController',
    'auth'  => 'AuthController',
    'cart'  => 'CartController',
    'order' => 'OrderController',
    'quote' => 'QuoteController'
];

if (!array_key_exists($controllerParam, $routes)) {
    $controllerParam = 'home';
}

$controllerClass = $routes[$controllerParam];
$controllerFile = __DIR__ . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        if (method_exists($controllerInstance, $actionParam)) {
            $controllerInstance->$actionParam();
            exit;
        }
    }
}

// Fallback por defecto si no existe la acción
header('Location: index.php?c=home&a=index');
exit;
