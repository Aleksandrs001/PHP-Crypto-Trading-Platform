<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserService;
use App\Template;

class LogInController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function showForm(): Template
    {
        $_SESSION['errors'] = null;
        return new Template('login.twig');
    }

    public function validate(): Redirect
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($this->userService->validateLogin($email, $password)) {
            $_SESSION['id'] = $this->userService->getUserId($email);
            return new Redirect('/market');
        } else {
            $_SESSION['errors'] = ['Invalid email or password.'];
            return new Redirect('/');
        }
    }
}