<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;

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

}
