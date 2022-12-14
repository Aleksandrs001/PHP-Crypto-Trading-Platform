<?php

namespace App\Repositories;

use App\Models\Transaction;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TransactionsRepository
{
    private static ?Connection $connection = null;

    public function __construct()
    {
        self::getConnection();
    }

    public static function getConnection(): ?Connection
    {
        if (self::$connection == null) {
            $connectionParams = [
                'dbname' => $_ENV['DATABASE_NAME'],
                'user' => $_ENV['USER'],
                'password' => $_ENV['PASSWORD'],
                'host' => $_ENV['HOST'],
                'driver' => 'pdo_mysql',
            ];
            self::$connection = DriverManager::getConnection($connectionParams);
        }

        return self::$connection;
    }

    public function storeTransaction(Transaction $transaction) // todo do bool
    {
        $query = "INSERT INTO transactions (stock, numberTraded, exchange, date, owner) VALUES ('{$transaction->getStockSymbol()}', '{$transaction->getNumberTraded()}', '{$transaction->getExchange()}', NOW(), '{$transaction->getOwner()}');";
        //$query = "INSERT INTO transactions (stock, numberTraded, exchange, owner) VALUES ('{$transaction->getStockSymbol()}', '{$transaction->getNumberTraded()}', '{$transaction->getExchange()}', '{$transaction->getOwner()}');";
        self::$connection->executeQuery($query);
    }

    public function getTransactions(int $owner): array
    {
        $query = "SELECT * FROM transactions WHERE owner = '{$owner}'";

        return self::$connection->fetchAllAssociative($query);
    }
}