<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository {

    public function __construct(protected User $user)
    {

    }

    public function create(array $data, $role_id) {

        $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role_id,
        ]);

        return $this->user;
    }

}
