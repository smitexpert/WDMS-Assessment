<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Notifications\ConfirmUserRegistrationMailNotification;
use App\Repositories\UserVerificationTokenRepository;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserAuthenticationService {

    protected $user_role_id;

    public function __construct() {
        $this->user_role_id = 2;
    }

    public function register(array $data) {
         return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $this->user_role_id,
        ]);
    }

    public function sendUserVerificationEmail(User $user) {

        $userVerificationTokenRepository = new UserVerificationTokenRepository();
        $userVerificationToken = $userVerificationTokenRepository->create($user, 'mail');

        $user->notify(new ConfirmUserRegistrationMailNotification($userVerificationToken));
    }

}
