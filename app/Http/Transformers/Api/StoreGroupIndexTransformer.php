<?php

namespace App\Http\Transformers\Api;

use App\Models\StoreGroup;

class StoreGroupIndexTransformer extends StoreGroupTransformer
{
    protected $defaultIncludes = [
        'stores',
    ];

    public function includeStores(StoreGroup $store_group)
    {
        return $this->collection($store_group->stores->active(), new StoreTransformer);
    }
}
