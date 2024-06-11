<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;

class WalletRepository {

    public function create(User $user, $userDefaultCurrency) {

        try {
            return Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'currency_id' => $userDefaultCurrency->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }


    public function getWalletByUserAndWalletId(User $user, $wallet_id) {
        return Wallet::where('user_id', $user->id)->where('id', $wallet_id)->first();
    }

}
