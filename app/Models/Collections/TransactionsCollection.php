<?php

namespace App\Models\Collections;

use App\Models\Transaction;

class TransactionsCollection
{
    private array $transactionsCollection = [];

    public function __construct(array $transactionsCollection = [])
    {
        foreach ($transactionsCollection as $cryptoCurrency) {
            $this->add($cryptoCurrency);
        }
    }

    public function add(Transaction $transaction): void
    {
        $this->transactionsCollection [] = $transaction;
    }

    public function get(): array
    {
        return $this->transactionsCollection;
    }
}
