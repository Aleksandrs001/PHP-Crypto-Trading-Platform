<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserDataRepository;
use App\Repositories\UserProfilesRepository;

class UserService
{
    private UserDataRepository $userRepository;
    private UserProfilesRepository $userProfilesRepository;

    public function __construct(UserDataRepository $userRepository, UserProfilesRepository $userProfilesRepository)
    {
        $this->userRepository = $userRepository;
        $this->userProfilesRepository = $userProfilesRepository;
    }

    public function validateLogin(string $email, string $password): bool
    {
        if (!$this->userRepository->emailExists($email)) {
            return false;
        }

        if (!password_verify($password, $this->userRepository->checkPassword($email, $password))) {
            return false;
        }

        return true;
    }

    public function registerUser(string $username, string $password, string $email): bool
    {
        if (empty($username) || empty($password) || empty($email)) {
            return false;
        }

        if ($this->userRepository->usernameExists($username)) {
            return false;
        }

        if ($this->userRepository->emailExists($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($username, $email, $hashedPassword, 0);

        return $this->userRepository->save($user);
    }

    public function initializeWallet(int $id): bool
    {
        return $this->userProfilesRepository->save($id, 0);
    }

    public function initializeUser(int $id): User
    {
        $name = $this->userRepository->getUserName($id);
        $balance = $this->userRepository->getUserBalance($id);

        return new User($name, null, null, $balance);
    }

    public function getUserId(string $email): int
    {
        return $this->userRepository->getUserId($email);
    }

    public function updateBalance(int $id, float $balance): bool
    {
        return $this->userProfilesRepository->updateBalance($id, $balance);
    }

    public function updateInventory(int $id, string $symbol, int $value): void
    {
        $this->userProfilesRepository->incrementValue($id, $symbol, $value);
    }

    public function getInventory(int $id): array
    {
        return $this->userProfilesRepository->getInventory($id);
    }
}
