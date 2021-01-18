<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\CastAttend;

class CastAttendShowTransformer extends CastAttendTransformer
{
    public function transform(CastAttend $cast_attend): array
    {
        return array_merge(parent::transform($cast_attend), [
            'storeName' => $cast_attend->store->store_name,
            'groupId' => $cast_attend->store->store_group_id,
            'groupName' => $cast_attend->store->storeGroup->group_name,
        ]);
    }
}
