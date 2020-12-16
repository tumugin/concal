<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateApiToken(): void
    {
        $user = factory(User::class)->create();
        $apiToken = resolve(UserAuthService::class)->createApiToken($user);
        $this->assertNotEmpty($apiToken);
    }
}
