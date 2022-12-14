<?php

namespace App\Services;

use App\Models\Collections\CryptoCurrencyCollection;
use App\Models\Collections\TransactionsCollection;
use App\Models\CryptoCurrency;
use App\Models\Transaction;
use App\Repositories\TransactionsRepository;

class TransactionsService
{
    private TransactionsRepository $transactionRepository;

    public function __construct(TransactionsRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function storeTransaction($stockSymbol, $numberTraded, $exchange, $owner): void
    {
        $transaction = new Transaction($stockSymbol, $numberTraded, $exchange, "template", $owner);
        $this->transactionRepository->storeTransaction($transaction);
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