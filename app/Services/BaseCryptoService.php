<?php

namespace App\Services;

use App\Models\Collections\CryptoCurrencyCollection;
use App\Models\CryptoCurrency;
use App\Models\User;
use App\Repositories\CryptoRepository\CryptoRepository;
use App\Repositories\UserAccountRepository\UserAccountRepository;
use App\Repositories\UserInventoryRepository\UserInventoryRepository;

class BaseCryptoService
{
    private CryptoRepository $cryptoRepository;
    private UserAccountRepository $userAccountRepository;
    private UserInventoryRepository $userInventoryRepository;

    public function __construct(
        CryptoRepository        $cryptoRepository,
        UserAccountRepository   $userAccountRepository,
        UserInventoryRepository $userInventoryRepository
    )
    {
        $this->cryptoRepository = $cryptoRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->userInventoryRepository = $userInventoryRepository;
    }

    public function getCryptoCurrencies(string $symbol = ""): CryptoCurrencyCollection
    {
        if ($symbol) {
            $articlesApiResponse = $this->cryptoRepository->fetchListing($symbol);
        } else {
            $articlesApiResponse = $this->cryptoRepository->fetchCryptoListings();
        }

        $cryptoCurrencies = new CryptoCurrencyCollection();
        foreach ($articlesApiResponse->data as $cryptoCurrency) {
            $cryptoCurrencies->add(new CryptoCurrency(
                $cryptoCurrency->name,
                $cryptoCurrency->symbol,
                $cryptoCurrency->cmc_rank,
                $cryptoCurrency->quote->EUR->price,
                $cryptoCurrency->quote->EUR->last_updated,
                $cryptoCurrency->quote->EUR->volume_24h,
                $cryptoCurrency->quote->EUR->volume_change_24h,
                $this->cryptoRepository->fetchCryptoLogo($cryptoCurrency->symbol)
            ));
        }
        return $cryptoCurrencies;
    }

    public function initializeUser(int $id): User
    {
        $name = $this->userAccountRepository->getUserName($id);
        $balance = $this->userInventoryRepository->getUserBalance($id);

        return new User($name, null, null, $balance);
    }
}