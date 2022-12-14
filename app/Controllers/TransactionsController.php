<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\TransactionsService;
use App\Services\UserService;
use App\Template;

class TransactionsController
{
    private TransactionsService $transactionService;
    private UserService $userService;

    public function __construct(TransactionsService $transactionService, UserService $userService)
    {
        $this->transactionService = $transactionService;
        $this->userService = $userService;
    }

    public function index(): Template
    {
        $transactions = $this->transactionService->getTransactionCollection($_SESSION['id']);

        return new Template(
            'market/transactions.twig',
            [
                    'transactions' => $transactions->get(),
                ]
        );
    }

    public function post(): Redirect
    {
        $this->userService->updateBalance($_SESSION['id'], $_POST['amount']);

        return new Redirect('/profile');
    }
}