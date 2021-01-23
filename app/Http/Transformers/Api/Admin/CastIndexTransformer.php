<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\Cast;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class CastIndexTransformer extends CastTransformer
{
    protected $defaultIncludes = [
        'stores',
        'latestCastAttend',
    ];

    public function includeStores(Cast $cast): Collection
    {
        return $this->collection($cast->stores, new StoreTransformer);
    }

    public function includeLatestCastAttend(Cast $cast): Item
    {
        return $this->item($cast->latestCastAttend, new CastAttendTransformer);
    }
}
