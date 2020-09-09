<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminGateProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define('has-admin-privilege', function (User $user) {
            // ユーザにAdmin APIの存在を知らせない為に404を返す
            return $user->isAdmin() ? Response::allow() : Response::deny(null, 404);
        });
    }
}
