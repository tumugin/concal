<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'user_name' => 'admin',
            'name' => 'admin',
            'password' => Hash::make('admin'),
            'email' => 'myskng@myskng.xyz',
            'user_privilege' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
