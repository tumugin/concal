<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUser(): void
    {
        $test_user_data = [
            'user_name' => 'erusa',
            'name' => 'エルサ',
            'password' => 'erusa_erusa_erusa',
            'email' => 'erusa@example.com',
            'user_privilege' => User::USER_PRIVILEGE_USER,
        ];
        User::createUser(
            $test_user_data['user_name'],
            $test_user_data['name'],
            $test_user_data['password'],
            $test_user_data['email'],
            $test_user_data['user_privilege']
        );
        $this->assertDatabaseHas(
            'users',
            Arr::only($test_user_data, [
                'user_name',
                'name',
                'email',
                'user_privilege',
            ])
        );
    }
}
