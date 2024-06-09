<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\WalletRepository;

class UserWalletService {

    protected $walletRepository;

    public function __construct() {
        $this->walletRepository = new WalletRepository();
    }

    public function createUserWallet(User $user) {

        $userDefaultCurrency = $this->getUserDefaultCurrency($user);

        $wallet = $this->walletRepository->create($user, $userDefaultCurrency);

        return $wallet;
    }

    public function getUserDefaultCurrency(User $user) {
        return $currency = Currency::first();
    }

    public function updateDenominationWalletBalance(Wallet $wallet, Transaction $transaction) {

        match($transaction->type) {
            'add' => $wallet->balance = $wallet->balance + $transaction->amount,
            'withdraw' => $wallet->balance = $wallet->balance - $transaction->amount,
        };

        $wallet->save();

    }


    public function isUserWallet(User $user, $wallet_id) {
        return $this->walletRepository->getWalletByUserAndWalletId($user, $wallet_id);
    }


    public function getWalletBalance(User $user, Wallet $wallet) {
        return $this->walletRepository->getWalletByUserAndWalletId($user, $wallet->id)->balance;
    }

}
