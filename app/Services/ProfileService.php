<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CryptoRepository\CryptoRepository;
use App\Repositories\UserAccountRepository\UserAccountRepository;
use App\Repositories\UserInventoryRepository\UserInventoryRepository;

class ProfileService extends BaseCryptoService
{
    private UserInventoryRepository $userInventoryRepository;
    private UserAccountRepository $userAccountRepository;

    public function __construct
    (
        UserInventoryRepository $userInventoryRepository,
        UserAccountRepository   $userAccountRepository,
        CryptoRepository        $cryptoRepository
    )
    {
        parent::__construct
        (
            $cryptoRepository,
            $userAccountRepository,
            $userInventoryRepository
        );
        $this->userInventoryRepository = $userInventoryRepository;
        $this->userAccountRepository = $userAccountRepository;
    }


    public
    function execute(int $id): array
    {

        $user = $this->initializeUser($id);

        return [
            'userInfo' => [
                'name' => $user->getName(),
                'balance' => $user->getBalance(),
                'avatar' => $user->getAvatar()
            ]];
    }

    public function updateBalance(int $id, float $balance): bool
    {
        return $this->userInventoryRepository->updateBalance($id, $balance);
    }

    public function getUserBalance($id): float
    {
        return $this->userInventoryRepository->getUserBalance($id);
    }

    public function initializeUser(int $id): User
    {
        $name = $this->userAccountRepository->getUserName($id);
        $balance = $this->userInventoryRepository->getUserBalance($id);
        $avatar = $this->userAccountRepository->getUserAvatar($id);

        return new User($name, null, null, $balance, $avatar);
    }

    public function returnUserViewParams(string $email): array
    {
        $id = $this->userAccountRepository->getUserId($email);

        $cryptoCurrencies = $this->getCryptoCurrencies();

        return ['user' => [
            'name' => $this->userAccountRepository->getUserName($id),
            'avatar' => $this->userAccountRepository->getUserAvatar($id),
            'email' => 'email'
        ],
            'cryptoCurrencies' => $cryptoCurrencies->get(),
            'userInventory' => $this->userInventoryRepository->getInventory($id)];
    }
}