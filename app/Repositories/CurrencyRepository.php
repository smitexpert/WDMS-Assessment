<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\User;

class CurrencyRepository {


    public function create(array $data) {
        return Currency::create([
            'name' => $data['name']
        ]);
    }


    public function all() {
        return Currency::all();
    }


    public function availableForUser(User $user) {
        return Currency::whereNotIn('id', $user->wallets->pluck('currency_id'))->get();
    }

}
