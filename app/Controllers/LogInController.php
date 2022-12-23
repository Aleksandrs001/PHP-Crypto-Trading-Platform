<?php

namespace App\Controllers;

use App\Services\LogInService;
use App\Template;

class LogInController
{
    private LogInService $logInService;

    public function __construct(LogInService $logInService)
    {
        $this->logInService = $logInService;
    }

    public function index(): Template
    {
        return new Template('login.twig');
    }

    public function validateCredentials(): Template
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            return new Template('/login.twig', ['error' => 'Please enter an email address']);
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Template('/login.twig', ['error' => 'Please enter a valid email address']);
        }

        if (empty($password)) {
            return new Template('/login.twig', ['error' => 'Please enter a password']);
        } elseif (!($this->logInService->passwordValidation($email, $password))) {
            return new Template('/login.twig', ['error' => 'Wrong Password']);
        }

        header('Location: /market');
        return new Template('/market/index.twig');
    }
}