<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Primitive;

class StoreShowCastTransformer extends CastTransformer
{
    protected $defaultIncludes = [
        'recentAttend',
    ];

    public function includeRecentAttend(Cast $cast): Primitive|Item
    {
        $cast_attend = $cast->latestCastAttend;
        if ($cast_attend === null) {
            return $this->primitive(null);
        }
        return $this->item($cast_attend, new CastAttendTransformer);
    }
}
