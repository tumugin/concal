<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;
use League\Fractal\Resource\Collection;

class CastIndexTransformer extends CastTransformer
{
    protected $defaultIncludes = ['stores'];

    public function includeStores(Cast $cast): Collection
    {
        return $this->collection($cast->stores->active, new StoreTransformer);
    }
}
