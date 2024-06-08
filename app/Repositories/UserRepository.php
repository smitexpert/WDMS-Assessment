<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository {

    public function create(array $data, $role_id) {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role_id,
        ]);

        return $user;
    }


    public function updateEmailVerification(User $user) {
        $user->update([
            'email_verified_at' => now()
        ]);

        return $user;
    }

}
