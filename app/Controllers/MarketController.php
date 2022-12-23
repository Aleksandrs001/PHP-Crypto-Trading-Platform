<?php

namespace App\Controllers;

use App\Services\MarketService;
use App\Template;

class MarketController
{
    private MarketService $marketService;

    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }

    public function index(): Template
    {
        $params = $this->marketService->execute();

        return new Template(
            'market/index.twig',
            $params
        );
    }

    public function search(): Template
    {
        $search = $_GET['search'];

        $params = $this->marketService->searchCrypto($search);

        return new Template(
            'market/searchResults.twig',
            $params
        );
    }

    public function buy(): Template
    {
        $number = (float)$_POST['number'];
        $priceTotal = $number * $_POST['price'];
        $params = $this->marketService->execute();
        $userBalance = $params['userInventory']['values']['balance'];

        if ($number == 0) {
            $params['TransactionError'] = "Not a valid input";
            return new Template('market/index.twig', $params);
        }

        if ($userBalance - $priceTotal < 0) {
            $params['TransactionError'] = "You don't have enough money";
            return new Template('market/index.twig', $params);
        }

        if (preg_match('/^\d+\.\d{3,}$/', $number)) {
            $params['TransactionError'] = "Don't use more than 2 decimals";
            return new Template('market/index.twig', $params);
        }

        $this->marketService->buyCrypto($_POST['symbol'], $number, $priceTotal);

        return new Template('market/index.twig', $this->marketService->execute());
    }

    public function sell(): Template
    {
        $number = (float)$_POST['number'];
        $priceTotal = $number * $_POST['price'];
        $params = $this->marketService->execute();
        $numberUserOwns = $params['userInventory']['values'][$_POST['symbol']];

        if ($number == 0) {
            $params['TransactionError'] = "Not a valid input";
            return new Template('market/index.twig', $params);
        }

        if ($numberUserOwns - $number < 0) {
            $params['TransactionError'] = "You can't sell more than you have";
            return new Template('market/index.twig', $params);
        }

        if (preg_match('/^\d+\.\d{3,}$/', $number)) {
            $params['TransactionError'] = "Don't use more than 2 decimals";
            return new Template('market/index.twig', $params);
        }

        $this->marketService->sellCrypto($_POST['symbol'], $number, $priceTotal);

        return new Template('market/index.twig', $this->marketService->execute());
    }

    public function short(): Template
    {
        $number = (float)$_POST['number'];
        $priceTotal = $number * $_POST['price'];

        $this->marketService->sellCrypto($_POST['symbol'], $number, $priceTotal);

        return new Template('market/index.twig', $this->marketService->execute());
    }
}