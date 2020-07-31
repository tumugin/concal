<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $hidden = [
        'password',
        'api_token',
    ];

    public function isAdmin(): bool
    {
        return $this->user_privilege === 'admin';
    }
}
