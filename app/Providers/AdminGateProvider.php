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
        Gate::define('has-admin-privilege', function (AdminUser $user) {
            // ユーザにAdmin APIの存在を知らせない為に404を返す
            return $user !== null ? Response::allow() : Response::deny(null, 404);
        });
    }
}
