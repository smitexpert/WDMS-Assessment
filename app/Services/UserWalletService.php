<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\WalletRepository;
use Exception;

class UserWalletService {

    protected $walletRepository;

    public function __construct() {
        $this->walletRepository = new WalletRepository();
    }

    public function createUserWallet(User $user, $currency_id = false) {

        $walletCurrency = $this->getUserCurrency($user, $currency_id == false ? 1 : $currency_id);

        if(!$walletCurrency)
            throw new Exception("Invalid wallet currency");

        if($this->getWalletByUserAndCurrencyId($user, $walletCurrency))
            throw new Exception("User already have wallet with currency.");

        $wallet = $this->walletRepository->create($user, $walletCurrency);

        return $wallet;
    }

    public function getUserCurrency(User $user, $currency_id = 1) {
        return $currency = Currency::find($currency_id);
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

    public function getWalletByUserAndCurrencyId(User $user, Currency $currency) {
        return Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->first();
    }

}
