<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;
use Carbon\Carbon;
use League\Fractal\Resource\Collection;

class CastShowTransformer extends CastTransformer
{
    protected $defaultIncludes = [
        'stores',
        'recentAttends',
    ];

    public function transform(Cast $cast): array
    {
        return parent::transform($cast);
    }

    public function includeStores(Cast $cast): Collection
    {
        $stores = $cast
            ->stores()
            ->active()
            ->with('storeGroup')
            ->get();
        return $this->collection($stores, new CastShowStoreTransformer);
    }

    public function includeRecentAttends(Cast $cast): Collection
    {
        $recent_cast_attends = $cast->castAttends()
            ->with('store')
            ->where('end_time', '>', Carbon::now())
            ->orderBy('end_time')
            ->limit(10);
        return $this->collection($recent_cast_attends, new CastShowRecentAttendsTransformer);
    }
}
