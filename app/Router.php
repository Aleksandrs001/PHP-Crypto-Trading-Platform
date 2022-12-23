<?php

namespace App;

use FastRoute\RouteCollector;
use App\Controllers\LogInController;
use App\Controllers\MarketController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;
use App\Controllers\StockInventoryController;
use App\Controllers\TransactionsController;

class Router
{
    public function addRoutes(RouteCollector $route)
    {
        $route->addRoute('GET', '/', [LogInController::class, 'index']);
        $route->addRoute('POST', '/', [LogInController::class, 'validateCredentials']);
        $route->addRoute('GET', '/register', [RegisterController::class, 'index']);
        $route->addRoute('POST', '/register', [RegisterController::class, 'registerNewUser']);
        $route->addRoute('GET', '/market', [MarketController::class, 'index']);
        $route->addRoute('GET', '/market/{symbol}', [MarketController::class, 'search']);
        $route->addRoute('POST', '/buy', [MarketController::class, 'buy']);
        $route->addRoute('POST', '/sell', [MarketController::class, 'sell']);
        $route->addRoute('POST', '/short', [MarketController::class, 'short']);
        $route->addRoute('GET', '/profile', [ProfileController::class, 'index']);
        $route->addRoute('POST', '/profile', [ProfileController::class, 'updateBalance']);
        $route->addRoute('GET', '/UserSearch', [ProfileController::class, 'userSearch']);
        $route->addRoute('GET', '/transactions', [TransactionsController::class, 'index']);
        $route->addRoute('GET', '/stockInventory', [StockInventoryController::class, 'index']);
        $route->addRoute('POST', '/sellOwned', [StockInventoryController::class, 'sellOwned']);
        $route->addRoute('POST', '/transfer', [StockInventoryController::class, 'transfer']);
        $route->addRoute('POST', '/buyShorted', [StockInventoryController::class, 'buyShorted']);
    }
}