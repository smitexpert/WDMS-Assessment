<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Notifications\ConfirmUserRegistrationMailNotification;
use App\Notifications\UserMfaNotification;
use App\Repositories\UserMfaRepository;
use App\Repositories\UserMfaVerifyRepository;
use App\Repositories\UserVerificationTokenRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class UserAuthenticationService {

    protected $user_role_id;
    protected $userVerificationTokenRepository;
    protected $mfaRepository;
    protected $userMfaVerifyRepository;

    public function __construct() {
        $this->user_role_id = 2;
        $this->userVerificationTokenRepository = new UserVerificationTokenRepository();
        $this->mfaRepository = new UserMfaRepository();
        $this->userMfaVerifyRepository = new UserMfaVerifyRepository();
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

        $result = $this->userVerificationTokenRepository->verifyUsingUserCode($user, $code);

        if($result === false) {
            return false;
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return true;;
    }


    public function userAuthAttempt($email, $password) {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
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


    public function enableUserMfa(User $user, $provider) {

        if($this->mfaRepository->findProviderByName($user, $provider))
            return false;

        return $this->mfaRepository->create($user, $provider);
    }

    public function removeUserMfa(User $user, $provider) {
        return $this->mfaRepository->delete($user, $provider);
    }

    public function getUserMfaProvider(User $user) {
        return $this->mfaRepository->findAllProviderByUser($user);
    }

    public function userMfaProviderByName(User $user, $provider) {
        return $this->mfaRepository->findProviderByName($user, $provider);
    }

    public function createUserMfaVerify(User $user, $provider) {
        return $this->userMfaVerifyRepository->create($user, $provider);
    }

    public function sendUserMfaCode(User $user, $code, $provider) {
        $user->notify(new UserMfaNotification($code, $provider));
    }

    public function verifyUserMfaCode(User $user, $code, $token_id) {
        $findMfa = $this->userMfaVerifyRepository->findByCode($user, $code);

        if(!$findMfa)
            throw new Exception("Invalid Code provided");

        if($findMfa->expire_at < now())
            throw new Exception("Code is expired");

        $this->userMfaVerifyRepository->verifyCode($user, $code, $token_id);
    }
}
