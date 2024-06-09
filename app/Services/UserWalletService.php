<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;

class UserWalletService {


    public function createUserWallet(User $user) {

        $userDefaultCurrency = $this->getUserDefaultCurrency($user);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'currency_id' => $userDefaultCurrency->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $wallet;
    }

    public function getUserDefaultCurrency(User $user) {
        return $currency = Currency::first();
    }

}
