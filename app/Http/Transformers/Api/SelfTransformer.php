<?php

namespace App\Http\Transformers\Api;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class SelfTransformer extends TransformerAbstract
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
