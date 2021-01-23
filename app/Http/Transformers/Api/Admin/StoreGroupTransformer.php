<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\StoreGroup;
use League\Fractal\TransformerAbstract;

class StoreGroupTransformer extends TransformerAbstract
{
    public function transform(StoreGroup $store_group): array
    {
        return [
            'id' => $store_group->id,
            'groupName' => $store_group->group_name,
        ];
    }
}
