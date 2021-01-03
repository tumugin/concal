<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\AdminUser;
use League\Fractal\TransformerAbstract;

class AdminUserTransformer extends TransformerAbstract
{
    public function transform(AdminUser $user): array
    {
        return [
            'id' => $user->id,
            'userName' => $user->user_name,
            'name' => $user->name,
            'email' => $user->email,
            'userPrivilege' => $user->user_privilege,
        ];
    }
}
