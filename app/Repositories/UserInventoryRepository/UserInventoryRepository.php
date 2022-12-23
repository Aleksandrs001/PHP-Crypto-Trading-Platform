<?php

namespace App\Repositories\UserInventoryRepository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class UserInventoryRepository implements UserInventoryRepositoryInterface
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

    function getInventory(int $id): array // returning object doesn't work in TWIG
    {
        $query = "SELECT * FROM user_inventories WHERE id = " . $id;

        $values = self::$connection->executeQuery($query)->fetchAssociative();

        return array(
            'values' => $values,
        );
    }

    function updateOwnedCryptoCount(int $id, string $symbol, float $value): void
    {
        $query = "SHOW COLUMNS FROM user_inventories LIKE '$symbol'";

        $result = self::$connection->executeQuery($query);

        if ($result->rowCount() == 0) {
            self::$connection->executeQuery("ALTER TABLE user_inventories ADD COLUMN $symbol FLOAT NOT NULL DEFAULT 0");
        }

        self::$connection->executeQuery("UPDATE user_inventories SET $symbol = $symbol + $value WHERE id = $id");
    }

    public function updateBalance(int $id, float $amount): bool
    {
        $query = "UPDATE user_inventories SET balance = balance + $amount WHERE id = '$id'";

        return self::$connection->executeQuery($query) ? true : false;
    }

    public function getUserBalance(int $id): float
    {
        $query = "SELECT balance FROM user_inventories WHERE id = '$id'";

        return self::$connection->fetchOne($query);
    }
}