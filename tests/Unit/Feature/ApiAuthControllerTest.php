<?php

namespace Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupPassport();
    }

    private const _TEST_USER_DATA = [
        'user_name' => 'erusa',
        'name' => 'エルサ',
        'password' => 'Erusa12345',
        'email' => 'erusa@example.com',
    ];

    public function testLoginWithEMail(): void
    {
        User::createUser(
            self::_TEST_USER_DATA['user_name'],
            self::_TEST_USER_DATA['name'],
            self::_TEST_USER_DATA['password'],
            self::_TEST_USER_DATA['email'],
            User::USER_PRIVILEGE_USER
        );
        $response = $this->post(
            '/api/login',
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
        User::createUser(
            self::_TEST_USER_DATA['user_name'],
            self::_TEST_USER_DATA['name'],
            self::_TEST_USER_DATA['password'],
            self::_TEST_USER_DATA['email'],
            User::USER_PRIVILEGE_USER
        );
        $response = $this->post(
            '/api/login',
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

    public function testLoginFailed(): void
    {
        factory(User::class)->create();
        $response = $this->post(
            '/api/login',
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

    public function testRevokeTokens(): void
    {
        $user = factory(User::class)->create();
        $token = $user->createApiToken();
        $response = $this
            ->withToken($token)
            ->post(
                '/api/token/revoke',
                []
            );
        $response->assertStatus(200);
    }
}
