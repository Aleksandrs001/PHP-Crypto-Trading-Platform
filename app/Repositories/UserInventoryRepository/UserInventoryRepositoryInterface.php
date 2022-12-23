<?php

namespace App\Repositories\UserInventoryRepository;

interface UserInventoryRepositoryInterface
{
    function getInventory(int $id): array;

    function updateOwnedCryptoCount(int $id, string $symbol, float $value): void;

    public function updateBalance(int $id, float $amount): bool;

    public function getUserBalance(int $id): float;
}