<?php

namespace App\Models;

class User
{
    private string $name;
    private ?string $email = null;
    private ?string $hashedPassword = null;
    private ?float $balance = null;

    public function __construct(string $name, ?string $email = null, ?string $hashedPassword = null, ?float $balance = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->balance = $balance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}