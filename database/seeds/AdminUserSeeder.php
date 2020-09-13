<?php

use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::createUser(
            'admin',
            'admin',
            $random_admin_password,
            'myskng@myskng.xyz',
            'admin'
        );
        $this->command->info("Admin user added with screen name 'admin' and password '${random_admin_password}'.");
    }
}
