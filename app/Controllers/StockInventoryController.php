<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\MarketService;
use App\Services\TransactionsService;
use App\Services\UserService;
use App\Template;

class StockInventoryController
{
    private UserService $userService;
    private MarketService $marketService;
    private TransactionsService $transactionService;

    public function __construct(UserService $userService, MarketService $marketService, TransactionsService $transactionService)
    {
        $this->userService = $userService;
        $this->marketService = $marketService;
        $this->transactionService = $transactionService;
    }

    public function index(): Template
    {
        $cryptoCurrencies = $this->marketService->execute();
        $user = $this->userService->initializeUser($_SESSION['id']);

        return new Template(
            'market/stockInventory.twig',
            [
                'cryptoCurrencies' => $cryptoCurrencies->get(),
                'userInventory' => $this->userService->getInventory($_SESSION['id']),
                'userInfo' => ['name' => $user->getName(),
                    'balance' => $user->getBalance()]
            ]
        );
    }

    public function post(): Redirect
    {
        $this->userService->updateBalance($_SESSION['id'], $_POST['amount']);

        return new Redirect('/profile');
    }

    public function buyOwned(): Redirect
    {
        $this->userService->updateBalance($_SESSION['id'], -$_POST['price']);
        $this->userService->updateInventory($_SESSION['id'], $_POST['symbol'], 1);
        $this->transactionService->storeTransaction($_POST['symbol'], 1, $_POST['price'], $_SESSION['id']);
        return new Redirect('/stockInventory');
    }

    public function sellOwned(): Redirect
    {

        $this->userService->updateBalance($_SESSION['id'], $_POST['price']);
        $this->userService->updateInventory($_SESSION['id'], $_POST['symbol'], -1);
        $this->transactionService->storeTransaction($_POST['symbol'], -1, $_POST['price'], $_SESSION['id']);
        return new Redirect('/stockInventory');
    }
}