<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Notifications\ConfirmUserRegistrationMailNotification;
use App\Repositories\UserVerificationTokenRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class UserAuthenticationService {

    protected $user_role_id;
    protected $userVerificationTokenRepository;

    public function __construct() {
        $this->user_role_id = 2;
        $this->userVerificationTokenRepository = new UserVerificationTokenRepository();
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

        if($this->userVerificationTokenRepository->getVerifyStatusByUser($user)) {
            throw new Exception("User already verified.");
        }

        $userVerificationToken = $this->userVerificationTokenRepository->generate($user, 'mail');

        $user->notify(new ConfirmUserRegistrationMailNotification($userVerificationToken));
    }


    public function verifyUserEmailCode(User $user, $code) {

        if($this->userVerificationTokenRepository->getVerifyStatusByUser($user)) {
            throw new Exception("User already verified.");
        }

        return $this->userVerificationTokenRepository->verifyUsingUserCode($user, $code);
    }


    public function userAuthAttempt($email, $password) {
        $user = User::where('email', $email)->first();

        if (!$user && !Hash::check($password, $user->password)) {
            return false;
        }

        $client = Client::where('password_client', true)->first();

        if(!$client)
            throw new Exception('Passport Client is not found!');


        $response = Http::asForm()->post(env('APP_URL').'/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ]);

        return $response->json();
    }

}
