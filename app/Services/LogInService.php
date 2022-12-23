<?php

namespace App\Services;

use App\Repositories\UserAccountRepository\UserAccountRepository;

class LogInService
{
    private UserAccountRepository $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository)
    {
        $this->userAccountRepository = $userAccountRepository;
    }

    public function passwordValidation(string $email, string $password): bool
    {
        if (!password_verify($password, $this->userAccountRepository->checkPassword($email))) {
            return false;
        }

        $_SESSION['id'] = $this->userAccountRepository->getUserId($email);
        $_SESSION['avatar'] = $this->userAccountRepository->getUserAvatar($_SESSION['id']);

        return true;
    }
}