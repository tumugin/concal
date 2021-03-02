<?php

namespace App\Http\Transformers\Api;

use App\Models\Store;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class StoreShowTransformer extends StoreTransformer
{
    protected $defaultIncludes = [
        'storeGroup',
        'casts',
    ];

    public function includeStoreGroup(Store $store): Item
    {
        return $this->item($store->storeGroup, new StoreGroupTransformer);
    }

    public function includeCasts(Store $store): Collection
    {
        return $this->collection(
            $store->casts->where('cast_disabled', '=', false),
            new StoreShowCastTransformer
        );
    }
}
