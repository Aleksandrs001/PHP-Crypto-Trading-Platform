<?php

namespace App\Models;

class User
{
    private string $name;
    private ?string $email;
    private ?string $hashedPassword;
    private ?float $balance;
    private ?int $avatar;

    public function __construct(string $name, ?string $email = null, ?string $hashedPassword = null, ?float $balance = null, ?int $avatar = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->balance = $balance;
        $this->avatar = $avatar;
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

    public function getAvatar(): int
    {
        return $this->avatar;
    }
}