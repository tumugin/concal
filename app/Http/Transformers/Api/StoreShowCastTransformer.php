<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;

class StoreShowCastTransformer extends CastTransformer
{
    protected $defaultIncludes = [
        'recentAttend',
    ];

    public function includeRecentAttend(Cast $cast)
    {
        return $this->item($cast->castAttends->first(), new CastAttendTransformer);
    }
}
