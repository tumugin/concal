<?php

namespace App\Http\Transformers\Api;

use App\Models\Store;
use League\Fractal\Resource\Item;

class CastShowStoreTransformer extends StoreTransformer
{
    protected $defaultIncludes = ['storeGroup'];

    public function includeStoreGroup(Store $store): Item
    {
        return $this->item($store->storeGroup, new StoreGroupTransformer);
    }
}
