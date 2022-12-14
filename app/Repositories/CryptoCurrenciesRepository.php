<?php

namespace App\Repositories;

use CoinMarketCap;

class CryptoCurrenciesRepository
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

    public function fetchOnelisting($symbol): object
    {
        return $this->api->cryptocurrency()->quotesLatest(['symbol' => $symbol, 'convert' => 'EUR']);
    }

    public function fetchCryptoLogoOLD(string $symbol): string
    {
        return "https://upload.wikimedia.org/wikipedia/commons/thumb/4/46/Bitcoin.svg/1024px-Bitcoin.svg.png";
        return $this->api->cryptocurrency()->info(['symbol' => $symbol])->data->{$symbol}->logo;
    }

    public function fetchCryptoLogo(string $symbol): string
    {
        // Check if the logo file already exists in the cache
        $logoPath = '../../logosCache/' . $symbol . '.png';
        $logoCheck = '../public/logosCache/' . $symbol . '.png';

        if (!file_exists($logoCheck)) {
            //var_dump($logoCheck);die;
            //var_dump("NOT FOUND".$symbol);die;
            // Fetch the logo from the API
            $logoUrl = $this->api->cryptocurrency()->info(['symbol' => $symbol])->data->{$symbol}->logo;
            $logoData = file_get_contents($logoUrl);

            // Save the logo to the cache
            file_put_contents($logoCheck, $logoData);
        }

        // Return the path to the cached logo file
        return $logoPath;
    }

}