<?php

namespace App\Providers;

use App\Models\AdminUser;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminGateProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define('has-admin-privilege', function ($user) {
            return $user instanceof AdminUser ? Response::allow() : Response::deny(null, 403);
        });
        Gate::define('has-super-admin-privilege', function ($user) {
            return $user instanceof AdminUser && $user->user_privilege === AdminUser::USER_PRIVILEGE_SUPER_ADMIN ?
                Response::allow() : Response::deny(null, 403);
        });
    }
}
