<?php

namespace App\Http\Transformers\Api\Admin;

use App\Http\Serializers\DefaultSerializer;
use App\Models\Cast;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

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

    public function includeLatestCastAttend(Cast $cast): Item|null
    {
        return $cast->latestCastAttend ?
            $this->item($cast->latestCastAttend, new CastAttendTransformer)
            : null;
    }
}
