<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $random_admin_password = Str::random(100);
        User::create([
            'screen_name' => 'admin',
            'name' => 'admin',
            'password' => Hash::make($random_admin_password),
            'api_token' => Str::random(512),
            'mail' => 'admin@example.com',
            'user_privilege' => 'admin',
        ]);
        $this->command->info("Admin user added with screen name 'admin' and password '${random_admin_password}'.");
    }
}
