<?php

namespace App\Repositories\CryptoRepository;

interface CryptoRepositoryInterface
{
    public function fetchCryptoListings(): object;

    public function fetchListing(string $symbol): object;

    public function fetchCryptoLogo(string $symbol): string;
}