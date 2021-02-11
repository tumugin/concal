<?php

namespace App\Http\Transformers\Api;

use App\Models\Store;
use League\Fractal\TransformerAbstract;

class StoreTransformer extends TransformerAbstract
{
    public function transform(Store $store): array
    {
        return [
            'id' => $store->id,
            'storeName' => $store->store_name,
            'storeGroupId' => $store->store_group_id,
            'storeDisabled' => $store->store_disabled,
        ];
    }
}
