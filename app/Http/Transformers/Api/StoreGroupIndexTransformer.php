<?php

namespace App\Http\Transformers\Api;

use App\Models\StoreGroup;
use League\Fractal\Resource\Collection;

class StoreGroupIndexTransformer extends StoreGroupTransformer
{
    protected $defaultIncludes = [
        'stores',
    ];

    public function includeStores(StoreGroup $store_group): Collection
    {
        return $this->collection($store_group->stores, new StoreTransformer);
    }
}
