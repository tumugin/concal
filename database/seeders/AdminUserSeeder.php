<?php

namespace Database\Seeders;

use App\Models\AdminUser;
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
        (new AdminUser([
            'user_name' => 'admin',
            'name' => 'admin',
            'password' => $random_admin_password,
            'email' => 'myskng@myskng.xyz',
            'user_privilege' => AdminUser::USER_PRIVILEGE_SUPER_ADMIN,
        ]))->save();
        $this->command->info("Admin user added with screen name 'admin' and password '${random_admin_password}'.");
    }
}
