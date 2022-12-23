<?php

namespace App\Controllers;

use App\Services\RegisterService;
use App\Template;

class RegisterController
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function index(): Template
    {
        return new Template('register.twig');
    }

    public function registerNewUser(): Template
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['passwordRepeat'];
        $avatar = (int)$_POST['avatar'];

        if (empty($name)) {
            return new Template('/register.twig', ['error' => 'Please enter a name']);
        }

        if (empty($email)) {
            return new Template('/register.twig', ['error' => 'Please enter an email address']);
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Template('/register.twig', ['error' => 'Please enter a valid email address']);
        }

        if (empty($password)) {
            return new Template('/register.twig', ['error' => 'Please enter a password']);
        }

        if (empty($passwordRepeat)) {
            return new Template('/register.twig', ['error' => 'Please repeat your password']);
        }

        if ($password !== $passwordRepeat) {
            return new Template('/register.twig', ['error' => 'Passwords do not match']);
        }

        $this->registerService->execute($name, $email, $password, $avatar);

        return new Template('/login.twig');
    }
}