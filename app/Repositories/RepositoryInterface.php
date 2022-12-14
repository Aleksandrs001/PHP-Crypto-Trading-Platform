<?php

namespace App\Repositories;

use App\Models\Collections\CryptoCurrencyCollection;

interface RepositoryInterface
{
    public static function getConnection(): CryptoCurrencyCollection;
    public function save(): CryptoCurrencyCollection;
}