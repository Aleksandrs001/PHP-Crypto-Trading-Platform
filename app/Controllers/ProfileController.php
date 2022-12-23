<?php

namespace App\Controllers;

use App\Services\ProfileService;
use App\Template;

class ProfileController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index(): Template
    {
        $params = $this->profileService->execute($_SESSION['id']);

        return new Template(
            'market/profile.twig',
            $params
        );
    }

    public function userSearch(): Template
    {
        $email = $_GET['search'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $params = $this->profileService->execute($_SESSION['id']);
            $params['SearchError'] = "Invalid email format";
            return new Template('market/profile.twig', $params);
        }

        $params = $this->profileService->returnUserViewParams($email);

        if (empty($params['user']['name'])) {

            $params = $this->profileService->execute($_SESSION['id']);
            $params['SearchError'] = "User not found";
            return new Template('market/profile.twig', $params);
        }

        return new Template('market/userView.twig', $params);
    }

    public function updateBalance(): Template
    {
        $currentBalance = $this->profileService->getUserBalance($_SESSION['id']);
        $requestedBalanceChange = $_POST['amount'];

        $params = $this->profileService->execute($_SESSION['id']);

        if ($requestedBalanceChange == 0) {
            $params['BalanceError'] = "You can't add or withdraw zero";
            return new Template('market/profile.twig', $params);
        }

        if ($requestedBalanceChange < 0 && $currentBalance + $requestedBalanceChange < 0) {
            $params['BalanceError'] = "You can't withdraw more than you have";
            return new Template('market/profile.twig', $params);
        }

        $this->profileService->updateBalance($_SESSION['id'], $_POST['amount']);

        return new Template
        (
            'market/profile.twig',
            $this->profileService->execute($_SESSION['id'])
        );
    }
}