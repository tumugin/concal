<?php

namespace Tests\Unit\User;

use App\Models\User;
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
        $apiToken = $user->createApiToken();
        $this->assertNotEmpty($apiToken);
    }
}
