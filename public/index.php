<?php

use App\Router;

require_once "../vendor/autoload.php";
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $routes = new Router();
    $routes->addRoutes($route);
});

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader);

//PHP-DI
$container = new DI\Container();

// Logout handler
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: /');
}

//Twig global variables
$twig->addGlobal('errors', $_SESSION);
$twig->addGlobal('query', $_GET);
$twig->addGlobal('avatar', $_SESSION);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = $container->get($controller)->$method($vars);

        if ($response instanceof \App\Template) {
            echo $twig->render($response->getPath(), $response->getParams());
        }

        if ($response instanceof \App\Redirect) {
            header('Location:' . $response->getUrl());
        }

        break;
}