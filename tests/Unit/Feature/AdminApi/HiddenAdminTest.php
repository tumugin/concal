<?php

namespace Tests\Unit\Feature\AdminApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class HiddenAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAdminUserAndLogin();
        $this->setupNormalUserAndLogin();
        $this->setupApiKey();
    }

    public function testCanAccessAdmin()
    {
        Config::set('host.admin_host', 'test');
        URL::forceRootUrl('http://test');
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(200);
    }

    public function testDenyAccess()
    {
        Config::set('host.admin_host', 'hogehoge');
        URL::forceRootUrl('http://test');
        $result = $this
            ->withHeaders($this->apiKeyHeader)
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.info'));
        $result->assertStatus(404);
    }
}
