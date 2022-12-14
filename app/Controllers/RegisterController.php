<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserService;
use App\Template;

class RegisterController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showForm(): Template
    {
        return new Template('register.twig');
    }

    public function store(): Redirect
    {

        if (($_POST['passwordRepeat'] !== $_POST['password'])
            || ($_POST['password'] == null)
            || ($_POST['email'] == null)) {

            $_SESSION['errors'] = ['Passwords do not match or fields are empty.'];

            return new Redirect('/register');
        } else {
            $this->userService->registerUser($_POST['name'], $_POST['password'], $_POST['email']);
            $this->userService->initializeWallet($this->userService->getUserId($_POST['email']));
            return new Redirect('/');
        }
    }
}