<?php

namespace Tests;

use App\Models\AdminUser;
use App\Models\User;
use App\Services\AdminUserAuthService;
use App\Services\UserAuthService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?string $adminApiKey = null;
    protected ?string $superAdminApiKey = null;
    protected ?string $userApiKey = null;
    protected array $apiKeyHeader = [];

    public function setupAdminUserAndLogin(): void
    {
        $user = factory(AdminUser::class)->create();
        $user->user_privilege = AdminUser::USER_PRIVILEGE_ADMIN;
        $user->save();
        $this->adminApiKey = resolve(AdminUserAuthService::class)->createApiToken($user);
    }

    public function setupSuperAdminUserAndLogin(): void
    {
        $user = factory(AdminUser::class)->create();
        $user->user_privilege = AdminUser::USER_PRIVILEGE_SUPER_ADMIN;
        $user->save();
        $this->superAdminApiKey = resolve(AdminUserAuthService::class)->createApiToken($user);
    }

    public function setupNormalUserAndLogin(): void
    {
        $user = factory(User::class)->create();
        $user->user_privilege = User::USER_PRIVILEGE_USER;
        $user->save();
        $this->userApiKey = resolve(UserAuthService::class)->createApiToken($user);
    }

    public function setupApiKey(): void
    {
        Config::set('apikey.app_api_key', 'test_key');
        $this->apiKeyHeader = [
            'X-API-KEY' => 'test_key'
        ];
    }
}
