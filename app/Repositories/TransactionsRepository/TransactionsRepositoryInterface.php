<?php

namespace App\Repositories\TransactionsRepository;

use App\Models\Transaction;

interface TransactionsRepositoryInterface
{
    public function storeTransaction(Transaction $transaction): bool;

    public function getTransactions(int $owner): array;
}