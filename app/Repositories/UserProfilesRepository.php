<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class UserProfilesRepository
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


    public function save(int $id, float $balance):bool
    {
        $query = "INSERT INTO users_profiles (id, balance) VALUES ('{$id}', '{$balance}')";

        return self::$connection->executeQuery($query) ? true : false;
    }

    function getInventory(int $id):array // returning object doesn't work in TWIG
    {
        $query = "SELECT * FROM users_profiles WHERE id = " . $id;

        $values = self::$connection->executeQuery($query)->fetchAssociative();

        return array(
            'values' => $values,
        );
    }

    function incrementValue(int $id, string $symbol, int $value):void
    {
        $query = "SHOW COLUMNS FROM users_profiles LIKE '$symbol'";

        $result = self::$connection->executeQuery($query);

        if ($result->rowCount() == 0) {
            self::$connection->executeQuery("ALTER TABLE users_profiles ADD COLUMN $symbol INT NOT NULL DEFAULT 0");
        }

        self::$connection->executeQuery("UPDATE users_profiles SET $symbol = $symbol + $value WHERE id = $id");
    }

    public function updateBalance(int $id, int $amount):bool
    {
        $query = "UPDATE users_profiles SET balance = balance + $amount WHERE id = '$id'";

        return self::$connection->executeQuery($query) ? true : false;
    }

}