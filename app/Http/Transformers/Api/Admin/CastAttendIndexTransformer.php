<?php

namespace App\Http\Transformers\Api\Admin;

use App\Models\CastAttend;
use League\Fractal\Resource\Item;

class CastAttendIndexTransformer extends CastAttendTransformer
{
    protected $defaultIncludes = [
        'store'
    ];

    public function includeStore(CastAttend $cast_attend): Item
    {
        return $this->item($cast_attend->store, new StoreTransformer);
    }
}
