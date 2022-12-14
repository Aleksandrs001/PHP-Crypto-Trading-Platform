<?php

namespace App\Services;

use App\Models\Collections\CryptoCurrencyCollection;
use App\Models\CryptoCurrency;
use App\Repositories\CryptoCurrenciesRepository;

class MarketService
{
    private CryptoCurrenciesRepository $cryptoCurrenciesRepository;

    public function __construct(CryptoCurrenciesRepository $cryptoCurrenciesRepository)
    {
        $this->cryptoCurrenciesRepository = $cryptoCurrenciesRepository;
    }

    public function getCryptoCurrencies(string $symbol = ""): CryptoCurrencyCollection
    {
        if ($symbol) {
            $articlesApiResponse = $this->cryptoCurrenciesRepository->fetchOnelisting($symbol);
        } else {
            $articlesApiResponse = $this->cryptoCurrenciesRepository->fetchCryptoListings();
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
                $this->cryptoCurrenciesRepository->fetchCryptoLogo($cryptoCurrency->symbol)
            ));
        }
        return $cryptoCurrencies;
    }

    public function execute($symbol=""): CryptoCurrencyCollection
    {
        return $this->getCryptoCurrencies($symbol);
    }
}