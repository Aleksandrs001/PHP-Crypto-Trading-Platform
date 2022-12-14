<?php

namespace App\Controllers;

use App\Services\MarketService;
use App\Services\UserService;
use App\Template;

class SingleStockViewController
{
    private UserService $userService;
    private MarketService $marketService;

    public function __construct(UserService $userService, MarketService $marketService)
    {
        $this->userService = $userService;
        $this->marketService = $marketService;
    }

    public function index(): Template
    {
        $cryptoCurrencies = $this->marketService->execute();
        $user = $this->userService->initializeUser($_SESSION['id']);

        return new Template(
            'market/stockView.twig',
            [
                'cryptoCurrencies' => $cryptoCurrencies->get(),
                'userInventory' => $this->userService->getInventory($_SESSION['id']),
                'userInfo' => ['name' => $user->getName(),
                    'balance' => $user->getBalance()]
            ]
        );
    }

    public function viewIndividual(array $vars): Template
    {
        $symbol = $vars['symbol'];

        $user = $this->userService->initializeUser($_SESSION['id']);

        $cryptoCurrencies = $this->marketService->execute($symbol);

        return new Template(
            'market/stockView.twig',
            [
                'cryptoCurrencies' => $cryptoCurrencies->get(),
                'userInventory' => $this->userService->getInventory($_SESSION['id']),
                'userInfo' => ['name' => $user->getName(),
                    'balance' => $user->getBalance()]
            ]
        );
    }
}