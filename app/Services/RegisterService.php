<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserAccountRepository\UserAccountRepository;

class RegisterService
{
    private UserAccountRepository $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository)
    {
        $this->userAccountRepository = $userAccountRepository;
    }

    public function execute(string $name, string $email, string $password, int $avatar): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($name, $email, $hashedPassword, 0, $avatar);

        $this->userAccountRepository->save($user);

        return true;
    }
}