<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testAttemptLoginWithUserName(): void
    {
        $test_user = factory(User::class)->create();
        $auth_user = UserAuthService::attemptLogin(
            $test_user->user_name,
            null,
            'uju_macha_milk',
            'web'
        );
        $this->assertEquals(
            $test_user->getAttributes(),
            $auth_user->getAttributes()
        );
        $this->assertEquals(
            $test_user->getAttributes(),
            UserAuthService::getCurrentUser('web')->getAttributes()
        );
    }

    public function testAttemptLoginWithEMail(): void
    {
        $test_user = factory(User::class)->create();
        $auth_user = UserAuthService::attemptLogin(
            null,
            $test_user->email,
            'uju_macha_milk',
            'web'
        );
        $this->assertEquals(
            $test_user->getAttributes(),
            $auth_user->getAttributes()
        );
        $this->assertEquals(
            $test_user->getAttributes(),
            UserAuthService::getCurrentUser('web')->getAttributes()
        );
    }
}
