<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(
            [
                'id' => 1,
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('roles')->insert(
            [
                'id' => 2,
                'name' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
