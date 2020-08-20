<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:client --personal --no-interaction --name=test_client');
    }

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

    public function testUpdateUserInfo(): void
    {
        $test_user_data = [
            'user_name' => 'erusa',
            'name' => 'エルサ',
            'password' => 'erusa_erusa_erusa',
            'email' => 'erusa@example.com',
        ];
        $user = factory(User::class)->create();
        $user->updateUserInfo($test_user_data);
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

    public function testCreateApiToken(): void
    {
        $user = factory(User::class)->create();
        $apiToken = $user->createApiToken();
        $this->assertNotEmpty($apiToken);
    }
}
