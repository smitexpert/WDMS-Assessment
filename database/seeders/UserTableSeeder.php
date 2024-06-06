<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('123321bd'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
