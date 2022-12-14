<?php

use App\Controllers\MarketController;
use App\Controllers\LogInController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;
use App\Controllers\StockInventoryController;
use App\Controllers\TransactionsController;
use App\Controllers\SingleStockViewController;
use App\Redirect;


require_once "../vendor/autoload.php";
session_start();

/*
 *
MD

Izmantot nodarbības iegūto, uztaisīt skatu kur var redzēt visu savu stoku apkopojumu,
būtu labi redzēt savas transakcijas tabula. (jauna tabula ?)
valet pārskats kautkāds, average gain loss. profil loss table.
 *
 */


$dotenv = Dotenv\Dotenv::createImmutable(substr(__DIR__, 0, -7)); // ../ beigas
$dotenv->load();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', [LogInController::class, 'showForm']);
    $route->addRoute('POST', '/', [LogInController::class, 'validate']);
    $route->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
    $route->addRoute('POST', '/register', [RegisterController::class, 'store']);
    $route->addRoute('GET', '/market', [MarketController::class, 'index']);
    $route->addRoute('GET', '/market/{symbol}', [SingleStockViewController::class, 'viewIndividual']);    $route->addRoute('POST', '/buy', [MarketController::class, 'buy']);
    $route->addRoute('POST', '/sell', [MarketController::class, 'sell']);
    $route->addRoute('GET', '/profile', [ProfileController::class, 'index']);
    $route->addRoute('POST', '/profile', [ProfileController::class, 'balance']);
    $route->addRoute('GET', '/transactions', [TransactionsController::class, 'index']);
    $route->addRoute('POST', '/transactions', [TransactionsController::class, 'post']);
    $route->addRoute('GET', '/stockInventory', [StockInventoryController::class, 'index']);
    $route->addRoute('POST', '/stockInventory', [StockInventoryController::class, 'post']);
    $route->addRoute('POST', '/buyOwned', [StockInventoryController::class, 'buyOwned']);
    $route->addRoute('POST', '/sellOwned', [StockInventoryController::class, 'sellOwned']);
});

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader);


$container = new DI\Container();


// Logout handler
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: /');
}


$authVariables = [
    \App\ViewVariables\AuthVariables::class
];

$twig->addGlobal('errors', $_SESSION);
$twig->addGlobal('query', $_GET);

foreach ($authVariables as $variable) {
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

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
        //$response = (new $controller)->{$method}($vars);

        if ($response instanceof \App\Template) {
            echo $twig->render($response->getPath(), $response->getParams());
        }

        if ($response instanceof \App\Redirect) {
            header('Location:' . $response->getUrl());
        }

        break;
}