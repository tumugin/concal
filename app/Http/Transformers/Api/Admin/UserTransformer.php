<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
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
