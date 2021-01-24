<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\CastAttend;

class CastAttendShowTransformer extends CastAttendTransformer
{
    protected $defaultIncludes = [
        'storeName',
        'groupId',
        'groupName',
    ];

    public function includeStoreName(CastAttend $cast_attend)
    {
        $this->primitive($cast_attend->store->store_name);
    }

    public function includeGroupId(CastAttend $cast_attend)
    {
        $this->primitive($cast_attend->store->store_group_id);
    }

    public function includeGroupName(CastAttend $cast_attend)
    {
        $this->primitive($cast_attend->store->storeGroup->group_name);
    }
}
