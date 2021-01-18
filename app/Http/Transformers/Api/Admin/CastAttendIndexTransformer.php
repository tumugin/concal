<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\CastAttend;

class CastAttendIndexTransformer extends CastAttendTransformer
{
    protected $defaultIncludes = [
        'store'
    ];

    public function includeStore(CastAttend $cast_attend): \League\Fractal\Resource\Item
    {
        return $this->item($cast_attend->store, new StoreTransformer);
    }
}
