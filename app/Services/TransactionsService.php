<?php

namespace App\Services;

use App\Models\Collections\TransactionsCollection;
use App\Models\Transaction;
use App\Repositories\TransactionsRepository\TransactionsRepository;
use App\Repositories\UserAccountRepository\UserAccountRepository;
use App\Repositories\UserInventoryRepository\UserInventoryRepository;

class TransactionsService
{
    private TransactionsRepository $transactionRepository;
    private UserAccountRepository $userAccountRepository;
    private UserInventoryRepository $userInventoryRepository;

    public function __construct
    (
        TransactionsRepository  $transactionRepository,
        UserAccountRepository   $userAccountRepository,
        UserInventoryRepository $userInventoryRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->userInventoryRepository = $userInventoryRepository;
    }

    public function execute($id): array
    {
        return [
            'userInfo' => [
                'name' => $this->userAccountRepository->getUserName($id),
                'balance' => $this->userInventoryRepository->getUserBalance($id)
            ],
            'transactions' => $this->getTransactionCollection($_SESSION['id'])->get()
        ];
    }

    public function getTransactionCollection($id): TransactionsCollection
    {
        $response = $this->transactionRepository->getTransactions($id);

        $transactions = new TransactionsCollection();
        foreach ($response as $transaction) {
            $transactions->add(new Transaction(
                $transaction['stock'],
                $transaction['numberTraded'],
                $transaction['exchange'],
                $transaction['date'],
                $transaction['owner'],
            ));
        }

        return $transactions;
    }
}