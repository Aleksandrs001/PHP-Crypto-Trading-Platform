<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserService;
use App\Template;

class ProfileController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): Template
    {
        $user = $this->userService->initializeUser($_SESSION['id']);

        return new Template(
            'market/profile.twig',
            [
                'userInfo' => [
                    'name' => $user->getName(),
                    'balance' => $user->getBalance()
                ]]
        );
    }

    public function balance(): Redirect
    {
        $this->userService->updateBalance($_SESSION['id'], $_POST['amount']);

        return new Redirect('/profile');
    }
}