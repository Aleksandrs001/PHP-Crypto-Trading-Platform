<?php

namespace App\Repositories;

use App\Models\user;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class UserDataRepository
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

    public function usernameExists(string $username): bool
    {
        $query = "SELECT * FROM users WHERE name = '{$username}'";

        return self::$connection->fetchOne($query) != null;
    }

    public function emailExists(string $email): bool
    {
        $query = "SELECT * FROM users WHERE email = '{$email}'";

        return self::$connection->fetchOne($query) != null;
    }

    public function save(User $user):bool
    {
        $query = "INSERT INTO users (name, email, password) VALUES ('{$user->getName()}', '{$user->getEmail()}', '{$user->getPassword()}')";

        return self::$connection->executeQuery($query) ? true : false;
    }

    public function checkPassword(string $email):string
    {
        $query = "SELECT password FROM users WHERE email = '$email'";

        return self::$connection->fetchOne($query);
    }

    public function getUserId(string $email):int
    {
        $query = "SELECT id FROM users WHERE email = '$email'";

        return self::$connection->fetchOne($query);
    }

    public function getUserName(int $id):string
    {
        $query = "SELECT name FROM users WHERE id = '$id'";

        return self::$connection->fetchOne($query);
    }

    public function getUserBalance(int $id):float
    {
        $query = "SELECT balance FROM users_profiles WHERE id = '$id'";

        return self::$connection->fetchOne($query);
    }


}