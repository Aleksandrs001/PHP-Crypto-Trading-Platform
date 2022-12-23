<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\CryptoRepository\CryptoRepository;
use App\Repositories\TransactionsRepository\TransactionsRepository;
use App\Repositories\UserAccountRepository\UserAccountRepository;
use App\Repositories\UserInventoryRepository\UserInventoryRepository;

class MarketService extends BaseCryptoService
{
    private UserInventoryRepository $userInventoryRepository;
    private TransactionsRepository $transactionsRepository;

    public function __construct
    (
        UserInventoryRepository $userInventoryRepository,
        TransactionsRepository  $transactionsRepository,
        CryptoRepository        $cryptoRepository,
        UserAccountRepository   $userAccountRepository
    )
    {
        parent::__construct
        (
            $cryptoRepository,
            $userAccountRepository,
            $userInventoryRepository
        );
        $this->userInventoryRepository = $userInventoryRepository;
        $this->transactionsRepository = $transactionsRepository;
    }


    public function execute(): array
    {
        $cryptoCurrencies = $this->getCryptoCurrencies();
        $user = $this->initializeUser($_SESSION['id']);

        return [
            'cryptoCurrencies' => $cryptoCurrencies->get(),
            'userInventory' => $this->userInventoryRepository->getInventory($_SESSION['id']),
            'userInfo' => ['name' => $user->getName(),
                'balance' => $user->getBalance()]
        ];
    }

    public function buyCrypto(string $symbol, float $number, float $price): bool
    {
        $this->userInventoryRepository->updateBalance($_SESSION['id'], -$price);

        $this->userInventoryRepository->updateOwnedCryptoCount($_SESSION['id'], $symbol, $number);

        $this->storeTransaction($symbol, $number, $price, $_SESSION['id']);

        return true;
    }

    public function storeTransaction(string $stockSymbol, float $numberTraded, float $exchange, int $owner): bool
    {
        $transaction = new Transaction($stockSymbol, $numberTraded, $exchange, "template", $owner);

        return $this->transactionsRepository->storeTransaction($transaction);
    }

    public function sellCrypto(string $symbol, float $number, float $price): bool
    {
        $this->userInventoryRepository->updateBalance($_SESSION['id'], $price);

        $this->userInventoryRepository->updateOwnedCryptoCount($_SESSION['id'], $symbol, -$number);

        $this->storeTransaction($symbol, -$number, $price, $_SESSION['id']);

        return true;
    }

    public function searchCrypto(string $search): array
    {
        $cryptoCurrencies = $this->getCryptoCurrencies($search);

        return [
            'cryptoCurrencies' => $cryptoCurrencies->get(),
            'userInventory' => $this->userInventoryRepository->getInventory($_SESSION['id']),
        ];
    }
}