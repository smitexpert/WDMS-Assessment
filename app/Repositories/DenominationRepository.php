<?php

namespace App\Repositories;

use App\Models\Denomination;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;

class DenominationRepository {


    public function __construct()
    {
    }

    public function findByWalletAndId(Wallet $wallet, $denomination_id) {
        return Denomination::where('id', $denomination_id)->where('wallet_id', $wallet->id)->first();
    }

    public function findByWalletId(Wallet $wallet) {
        return Denomination::with('wallet')->where('wallet_id', $wallet->id)->get();
    }

    public function create(User $user, array $data) {

        try {

            return Denomination::create([
                'wallet_id' => $data['wallet_id'],
                'name' => $data['name'],
                'denomination' => $data['denomination'],
                'quantity' => $data['quantity'],
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }



    }


    public function delete(Denomination $denomination) {

        try {
            return $denomination->delete();
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
