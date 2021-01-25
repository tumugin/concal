<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\Store;

class StoreShowTransformer extends StoreTransformer
{
    protected $defaultIncludes = [
        'storeGroup',
        'casts',
    ];

    public function includeStoreGroup(Store $store)
    {
        $this->item($store->storeGroup, new StoreGroupTransformer);
    }

    public function includeCasts(Store $store)
    {
        $this->collection($store->casts, new CastTransformer);
    }
}
