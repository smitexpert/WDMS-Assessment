<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserVerifyToken;
use Exception;

class UserVerificationTokenRepository {

    protected $code;

    public function __construct()
    {
        $this->code = rand(1111, 9999);
    }

    public function generate(User $user, $provider) {

        return UserVerifyToken::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'code' => $this->code,
            'expire_at' => now()->addMinutes(30)
        ]);
    }

    public function verifyUsingUserCode(User $user, $code) {
        $userVerifyToken = UserVerifyToken::where('user_id', $user->id)
                                            ->where('code', $code)
                                            ->where('status', 0)
                                            ->first();

        if(!$userVerifyToken)
            throw new Exception("Verification code '$code' not found.");


        if($userVerifyToken->expire_at < now())
            throw new Exception("Verification code '$code' expired.");

        $userVerifyToken->update([
           'status' => 1,
        ]);

        return true;
    }

    public function getVerifyStatusByUser(User $user) {
        $userVerifyToken = UserVerifyToken::where('user_id', $user->id)->where('status', 1)->first();

        if($userVerifyToken)
            return true;

        return false;

    }

}
