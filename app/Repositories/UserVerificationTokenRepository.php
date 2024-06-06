<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserVerifyToken;

class UserVerificationTokenRepository {

    public function create(User $user, $provider) {

        $code = rand(1111, 9999);

        return UserVerifyToken::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'code' => $code,
            'expire_at' => now()->addMinutes(30)
        ]);
    }

}
