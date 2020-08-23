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
        $this->setupPassport();
        $this->setupAdminUserAndLogin();
        $this->setupNormalUserAndLogin();
    }

    public function testAdminUserCanUse()
    {
        $result = $this
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(200);
    }

    public function testNormalUserCanNotUse()
    {
        $result = $this
            ->withToken($this->userApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(403);
    }

    public function testUnauthorizedUserCanNotUse()
    {
        $result = $this
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(401);
    }
}
