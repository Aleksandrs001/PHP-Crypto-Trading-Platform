<?php

namespace App\Repositories\CryptoRepository;

use CoinMarketCap;

class CryptoRepository implements CryptoRepositoryInterface
{
    private CoinMarketCap\Api $api;

    public function __construct()
    {
        $this->api = new CoinMarketCap\Api($_ENV['COIN_MARKET_CAP_API']);
    }

    public function fetchCryptoListings(): object
    {
        return $this->api->cryptocurrency()->listingsLatest(['limit' => 12, 'convert' => 'EUR']);
    }

    public function fetchListing($symbol): object
    {
        return $this->api->cryptocurrency()->quotesLatest(['symbol' => $symbol, 'convert' => 'EUR']);
    }

    public function fetchCryptoLogo(string $symbol): string
    {
        $logoPath = '../../logosCache/' . $symbol . '.png';
        $logoCheck = '../public/logosCache/' . $symbol . '.png';

        if (!file_exists($logoCheck)) {
            $logoUrl = $this->api->cryptocurrency()->info(['symbol' => $symbol])->data->{$symbol}->logo;
            $logoUrl = str_replace("64x64", "128x128", $logoUrl);
            $logoData = file_get_contents($logoUrl);

            file_put_contents($logoCheck, $logoData);
        }

        return $logoPath;
    }
}