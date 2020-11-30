<?php

namespace Feature\AdminApi;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProxyLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupApiKey();
        self::prepareAdminUser();
    }

    private const _TEST_USER_DATA = [
        'user_name' => 'erusa',
        'name' => 'エルサ',
        'password' => 'Erusa12345',
        'email' => 'erusa@example.com',
    ];

    private function prepareAdminUser(): void
    {
        factory(AdminUser::class)->create([
            'user_name' => self::_TEST_USER_DATA['user_name'],
            'name' => self::_TEST_USER_DATA['name'],
            'password' => Hash::make(self::_TEST_USER_DATA['password']),
            'email' => self::_TEST_USER_DATA['email'],
            'user_privilege' => AdminUser::USER_PRIVILEGE_ADMIN,
        ]);
    }

    public function testProxyLogin()
    {
        Config::set('host.oauth2_proxy_enabled', true);
        Http::fake([
            '*' => Http::response([
                'email' => self::_TEST_USER_DATA['email'],
            ], 200),
        ]);
        $response = $this
            ->withHeaders($this->apiKeyHeader)
            ->post('/api/admin/proxy_login');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'apiToken',
        ]);
    }
}
