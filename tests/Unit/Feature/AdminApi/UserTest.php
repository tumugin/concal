<?php

namespace Feature\AdminApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupPassport();
        $this->setupAdminUserAndLogin();
    }

    public function testGetAllUsers()
    {
        $result = $this
            ->withToken($this->adminApiKey)
            ->getJson(URL::route('api.admin.users.index', [
                'page' => 1,
            ]));
        $result->assertStatus(200);
    }
}
