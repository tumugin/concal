<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminGateProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define('has-admin-privilege', function (User $user) {
            return $user->isAdmin();
        });
    }
}
