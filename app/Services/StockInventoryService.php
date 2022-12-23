<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\CryptoRepository\CryptoRepository;
use App\Repositories\TransactionsRepository\TransactionsRepository;
use App\Repositories\UserAccountRepository\UserAccountRepository;
use App\Repositories\UserInventoryRepository\UserInventoryRepository;

class StockInventoryService extends BaseCryptoService
{
    private UserInventoryRepository $userInventoryRepository;
    private UserAccountRepository $userAccountRepository;
    private TransactionsRepository $transactionsRepository;

    public function __construct(
        CryptoRepository        $cryptoCurrenciesRepository,
        UserInventoryRepository $userInventoryRepository,
        UserAccountRepository   $userAccountRepository,
        TransactionsRepository  $transactionsRepository
    )
    {
        parent::__construct(
            $cryptoCurrenciesRepository,
            $userAccountRepository,
            $userInventoryRepository
        );
        $this->userInventoryRepository = $userInventoryRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->transactionsRepository = $transactionsRepository;
    }

    public function execute(): array
    {
        $cryptoCurrencies = $this->getCryptoCurrencies();

        $user = $this->initializeUser($_SESSION['id']);

        return [
            'cryptoCurrencies' => $cryptoCurrencies->get(),
            'userInventory' => $this->userInventoryRepository->getInventory($_SESSION['id']),
            'userInfo' => [
                'name' => $user->getName(),
                'balance' => $user->getBalance()
            ]
        ];
    }

    public function sellOwned(string $symbol, float $price, float $number): bool
    {
        $this->userInventoryRepository->updateBalance($_SESSION['id'], $price);

        $this->userInventoryRepository->updateOwnedCryptoCount($_SESSION['id'], $symbol, $number);

        $transaction = new Transaction($symbol, -$number, $price, "template", $_SESSION['id']);

        $this->transactionsRepository->storeTransaction($transaction);

        return true;
    }

    public function buyCrypto(string $symbol, float $number, float $price): bool
    {
        $this->userInventoryRepository->updateBalance($_SESSION['id'], -$price);

        $this->userInventoryRepository->updateOwnedCryptoCount($_SESSION['id'], $symbol, $number);

        $this->storeTransaction($symbol, $number, $price, $_SESSION['id']);

        return true;
    }

    public function transfer(string $email, float $numberToTransfer, string $symbol, float $price): bool
    {
        $id = $this->userAccountRepository->getUserId($email);

        $this->userInventoryRepository->updateOwnedCryptoCount($_SESSION['id'], $symbol, -$numberToTransfer);
        $this->storeTransaction($symbol, -$numberToTransfer, $price, $_SESSION['id']);

        $this->userInventoryRepository->updateOwnedCryptoCount($id, $symbol, $numberToTransfer);
        $this->storeTransaction($symbol, $numberToTransfer, $price, $id);

        return true;
    }

    public function storeTransaction($stockSymbol, $numberTraded, $exchange, $owner): bool
    {
        $transaction = new Transaction($stockSymbol, $numberTraded, $exchange, "template", $owner);

        return $this->transactionsRepository->storeTransaction($transaction);
    }

    public function emailCheck(string $email): bool
    {
        $id = $this->userAccountRepository->getUserId($email);


        if ($id === 0) {
            return false;
        } else {
            return true;
        }
    }
}