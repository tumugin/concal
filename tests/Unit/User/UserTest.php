<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupPassport();
    }

    public function testCreateUser(): void
    {
        $test_user_data = [
            'user_name' => 'erusa',
            'name' => 'エルサ',
            'password' => 'Erusa_erusa_erusa_1',
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
            'password' => 'Erusa_erusa_erusa_1',
            'email' => 'erusa@example.com',
            'user_privilege' => User::USER_PRIVILEGE_USER,
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

    public function testRevokeAllPersonalAccessTokens(): void
    {
        $user = factory(User::class)->create();
        $user->createApiToken();
        $this->assertEquals(1, $user->tokens->count());
        $user->revokeAllPersonalAccessTokens();
        $this->assertEquals(
            0,
            $user->tokens->where('revoked', '=', false)->count()
        );
        $this->assertEquals(
            1,
            $user->tokens->where('revoked', '=', true)->count()
        );
    }
}
