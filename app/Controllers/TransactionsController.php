<?php

namespace App\Controllers;

use App\Services\TransactionsService;
use App\Template;

class TransactionsController
{
    private TransactionsService $transactionService;

    public function __construct(TransactionsService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(): Template
    {
        $params = $this->transactionService->execute($_SESSION['id']);

        return new Template(
            'market/transactions.twig',
            $params
        );
    }
}