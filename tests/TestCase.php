<?php

namespace Tests;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?string $adminApiKey = null;
    protected ?string $userApiKey = null;

    public function setupPassport(): void
    {
        $this->artisan('passport:client --personal --no-interaction --name=test_client');
    }

    public function setupAdminUserAndLogin(): void
    {
        $user = factory(AdminUser::class)->create();
        $user->user_privilege = AdminUser::USER_PRIVILEGE_SUPER_ADMIN;
        $user->save();
        $this->adminApiKey = $user->createApiToken();
    }

    public function setupNormalUserAndLogin(): void
    {
        $user = factory(User::class)->create();
        $user->user_privilege = User::USER_PRIVILEGE_USER;
        $user->save();
        $this->userApiKey = $user->createApiToken();
    }
}
