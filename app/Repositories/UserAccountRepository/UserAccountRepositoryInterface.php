<?php

namespace App\Repositories\UserAccountRepository;

use App\Models\User;

interface UserAccountRepositoryInterface
{
    public function save(User $user): bool;

    public function checkPassword(string $email): string;

    public function getUserId(string $email): int;

    public function getUserName(int $id): string;

    public function getUserAvatar(int $id): int;
}