<?php

namespace Tests\Unit\Feature\AdminApi;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApiKey();
    }

    private const _TEST_USER_DATA = [
        'user_name' => 'erusa',
        'name' => 'エルサ',
        'password' => 'Erusa12345',
        'email' => 'erusa@example.com',
    ];

    public function testLoginWithEMail(): void
    {
        AdminUser::createUser(
            self::_TEST_USER_DATA['user_name'],
            self::_TEST_USER_DATA['name'],
            self::_TEST_USER_DATA['password'],
            self::_TEST_USER_DATA['email'],
            AdminUser::USER_PRIVILEGE_ADMIN
        );
        $response = $this
            ->withHeaders($this->apiKeyHeader)
            ->postJson(
                '/api/admin/login',
                [
                    'email' => self::_TEST_USER_DATA['email'],
                    'password' => self::_TEST_USER_DATA['password'],
                ]
            );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'apiToken',
        ]);
    }

    public function testLoginWithUserName(): void
    {
        AdminUser::createUser(
            self::_TEST_USER_DATA['user_name'],
            self::_TEST_USER_DATA['name'],
            self::_TEST_USER_DATA['password'],
            self::_TEST_USER_DATA['email'],
            AdminUser::USER_PRIVILEGE_ADMIN
        );
        $response = $this
            ->withHeaders($this->apiKeyHeader)
            ->postJson(
                '/api/admin/login',
                [
                    'userName' => self::_TEST_USER_DATA['user_name'],
                    'password' => self::_TEST_USER_DATA['password'],
                ]
            );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'apiToken',
        ]);
    }

    public function testCanNotLoginWithNormalUser(): void
    {
        User::createUser(
            self::_TEST_USER_DATA['user_name'],
            self::_TEST_USER_DATA['name'],
            self::_TEST_USER_DATA['password'],
            self::_TEST_USER_DATA['email'],
            User::USER_PRIVILEGE_USER
        );
        $response = $this
            ->withHeaders($this->apiKeyHeader)
            ->postJson(
                '/api/admin/login',
                [
                    'userName' => self::_TEST_USER_DATA['user_name'],
                    'password' => self::_TEST_USER_DATA['password'],
                ]
            );
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testLoginFailed(): void
    {
        factory(AdminUser::class)->create();
        $response = $this
            ->withHeaders($this->apiKeyHeader)
            ->postJson(
                '/api/admin/login',
                [
                    'userName' => 'hoge',
                    'password' => 'hoge',
                ]
            );
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }
}
