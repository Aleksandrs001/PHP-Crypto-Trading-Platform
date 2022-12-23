<?php

namespace App\Repositories\UserAccountRepository;

use App\Models\user;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class UserAccountRepository implements UserAccountRepositoryInterface
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

    public function save(User $user): bool
    {
        $query = "INSERT INTO user_accounts (name, email, password, avatar) VALUES ('{$user->getName()}', '{$user->getEmail()}', '{$user->getPassword()}', '{$user->getAvatar()}')";

        return self::$connection->executeQuery($query) ? true : false;
    }

    public function checkPassword(string $email): string
    {
        $query = "SELECT password FROM user_accounts WHERE email = '$email'";

        return self::$connection->fetchOne($query);
    }

    public function getUserId(string $email): int
    {
        $query = "SELECT id FROM user_accounts WHERE email = '$email'";

        return self::$connection->fetchOne($query);
    }

    public function getUserName(int $id): string
    {
        $query = "SELECT name FROM user_accounts WHERE id = '$id'";

        return self::$connection->fetchOne($query);
    }

    public function getUserAvatar(int $id): int
    {
        $query = "SELECT avatar FROM user_accounts WHERE id = '$id'";

        return self::$connection->fetchOne($query);
    }
}