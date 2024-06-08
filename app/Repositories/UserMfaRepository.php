<?php

namespace App\Repositories;

use App\Models\MfaProvider;
use App\Models\User;

class UserMfaRepository {


    public function create(User $user, $provider) {

        $mfa = MfaProvider::create([
            'user_id' => $user->id,
            'provider' => $provider
        ]);

        return $mfa;
    }

    public function findProviderByName(User $user, $provider) {
        $mfa = MfaProvider::where('user_id', $user->id)
            ->where('provider', $provider)
            ->first();

        return $mfa;
    }

    public function delete(User $user, $provider) {
        $mfa = MfaProvider::where('user_id', $user->id)
            ->where('provider', $provider)
            ->delete();

        return $mfa;
    }

}
