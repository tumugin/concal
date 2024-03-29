<?php

namespace Feature\AdminApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class GateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAdminUserAndLogin();
        $this->setupSuperAdminUserAndLogin();
        $this->setupNormalUserAndLogin();
        $this->setupApiKey();
    }

    public function testSuperAdminUserCanUse()
    {
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->superAdminApiKey)
            ->getJson(URL::route('api.admin.admin_users.index'));
        $result->assertStatus(200);
    }

    public function testNormalAdminUserCanNotUse()
    {
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.admin_users.index'));
        $result->assertStatus(403);
    }

    public function testAdminUserCanUse()
    {
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(200);
    }

    public function testNormalUserCanNotUse()
    {
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->userApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(401);
    }

    public function testUnauthorizedUserCanNotUse()
    {
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(401);
    }
}
