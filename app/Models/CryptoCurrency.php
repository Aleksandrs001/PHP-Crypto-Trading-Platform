<?php

namespace App\Models;

class CryptoCurrency
{
    private string $name;
    private string $symbol;
    private int $rank;
    private float $price;
    private string $lastUpdate;
    private float $totalVolume;
    private float $volumeChange;
    private string $logo;

    public function __construct
    (
        string $name,
        string $symbol,
        int    $rank,
        float  $price,
        string $lastUpdate,
        float  $totalVolume,
        float  $volumeChange,
        string $logo
    )
    {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->rank = $rank;
        $this->price = $price;
        $this->lastUpdate = $lastUpdate;
        $this->totalVolume = $totalVolume;
        $this->volumeChange = $volumeChange;
        $this->logo = $logo;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getLastUpdate(): string
    {
        return $this->lastUpdate;
    }

    public function getTotalVolume(): float
    {
        return $this->totalVolume;
    }

    public function getVolumeChange(): float
    {
        return $this->volumeChange;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }
}