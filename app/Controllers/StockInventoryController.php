<?php

namespace App\Controllers;

use App\Services\StockInventoryService;
use App\Template;

class StockInventoryController
{
    private StockInventoryService $stockInventoryService;

    public function __construct(StockInventoryService $stockInventoryService)
    {
        $this->stockInventoryService = $stockInventoryService;
    }

    public function index(): Template
    {
        $params = $this->stockInventoryService->execute();

        return new Template(
            'market/stockInventory.twig',
            $params
        );
    }

    public function sellOwned(): Template
    {
        $number = (float)$_POST['number'];
        $priceTotal = $number * $_POST['price'];
        $params = $this->stockInventoryService->execute();
        $numberUserOwns = $params['userInventory']['values'][$_POST['symbol']];

        if ($number == 0) {
            $params['OwnedTransactionError'] = "Not a valid input";
            return new Template('market/stockInventory.twig', $params);
        }

        if ($numberUserOwns - $number < 0) {
            $params['OwnedTransactionError'] = "You can't sell more than you have";
            return new Template('market/stockInventory.twig', $params);
        }

        if (preg_match('/^\d+\.\d{3,}$/', $number)) {
            $params['TransactionError'] = "Don't use more than 2 decimals";
            return new Template('market/index.twig', $params);
        }

        $this->stockInventoryService->sellOwned($_POST['symbol'], $priceTotal, -$number);

        return new Template('market/stockInventory.twig', $this->stockInventoryService->execute());
    }

    public function transfer(): Template
    {
        $email = $_POST['search'];
        $numberToTransfer = $_POST['number'];
        $priceTotal = $numberToTransfer * $_POST['price'];
        $params = $this->stockInventoryService->execute();
        $numberUserOwns = $params['userInventory']['values'][$_POST['symbol']];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $params = $this->stockInventoryService->execute();
            $params['OwnedTransactionError'] = "Not a valid email";
            return new Template('market/stockInventory.twig', $params);
        }

        if (!($this->stockInventoryService->emailCheck($email))) {
            $params = $this->stockInventoryService->execute();
            $params['OwnedTransactionError'] = "User by given email not found";
            return new Template('market/stockInventory.twig', $params);
        }

        if ($numberToTransfer == 0) {
            $params = $this->stockInventoryService->execute();
            $params['OwnedTransactionError'] = "Not a valid input";
            return new Template('market/stockInventory.twig', $params);
        }

        if (preg_match('/^\d+\.\d{3,}$/', $numberToTransfer)) {
            $params['TransactionError'] = "Don't use more than 2 decimals";
            return new Template('market/index.twig', $params);
        }

        if ($numberUserOwns - $numberToTransfer < 0) {
            $params = $this->stockInventoryService->execute();
            $params['OwnedTransactionError'] = "You can't transfer more than you have";
            return new Template('market/stockInventory.twig', $params);
        }

        $this->stockInventoryService->transfer($email, $numberToTransfer, $_POST['symbol'], $priceTotal);

        return new Template('market/stockInventory.twig', $this->stockInventoryService->execute());
    }

    public function buyShorted(): Template
    {
        $number = (float)$_POST['number'];
        $priceTotal = $number * $_POST['price'];
        $params = $this->stockInventoryService->execute();
        $userBalance = $params['userInventory']['values']['balance'];

        if ($number == 0) {
            $params['ShortedTransactionError'] = "Not a valid input";
            return new Template('market/stockInventory.twig', $params);
        }

        if ($userBalance - $priceTotal < 0) {
            $params['ShortedTransactionError'] = "You don't have enough money";
            return new Template('market/stockInventory.twig', $params);
        }

        if (preg_match('/^\d+\.\d{3,}$/', $number)) {
            $params['TransactionError'] = "Don't use more than 2 decimals";
            return new Template('market/index.twig', $params);
        }

        $this->stockInventoryService->buyCrypto($_POST['symbol'], $number, $priceTotal);

        return new Template('market/stockInventory.twig', $this->stockInventoryService->execute());
    }
}