<?php

namespace App\Repositories;

use App\Models\MfaVerify;
use App\Models\User;

class UserMfaVerifyRepository {

    protected $code;

    public function __construct()
    {
        $this->code = rand(1111, 9999);
    }

    public function create(User $user, $provider) {
        return MfaVerify::create([
            'user_id' => $user->id,
            'code' => $this->code,
            'expire_at' => now()->addMinutes(10),
            'status' => 0,
        ]);
    }

    public function findByCode(User $user, $code) {
        return MfaVerify::where('user_id', $user->id)->where('code', $code)->where('status', 0)->first();
    }

    public function verifyCode(User $user, $code, $token_id) {
        $findMfa = $this->findByCode($user, $code);

        $findMfa->update([
            'token_id' => $token_id,
           'status' => 1,
        ]);

        return true;
    }


    public function checkUserToken(User $user, $token_id) {
        return MfaVerify::where('user_id', $user->id)->where('token_id', $token_id)->first();
    }

}
