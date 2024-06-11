<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class TransactionRepository {

    public function create(User $user, array $data, $type) {

            try {

                return Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $data['wallet_id'],
                    'type' => $type,
                    'amount' => $data['amount']
                ]);

            } catch (\Throwable $th) {
                throw $th;
            }

    }


    public function getTransactionsByUser(User $user) {
        return Transaction::with('wallet.currency')->where('user_id', $user->id)->orderBy('id', 'DESC')->paginate();
    }

    public function getTransactionsByWallet(User $user, Wallet $wallet) {
        return Transaction::with('wallet.currency')->where('user_id', $user->id)->where('wallet_id', $wallet->id)->orderBy('id', 'DESC')->get();
    }

}
