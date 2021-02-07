<?php

namespace App\Http\Transformers\Api;

use App\Models\CastAttend;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CastAttendTransformer extends TransformerAbstract
{
    public function transform(CastAttend $cast_attend): array
    {
        return [
            'id' => $cast_attend->id,
            'castId' => $cast_attend->cast_id,
            'storeId' => $cast_attend->store_id,
            'attendInfo' => $cast_attend->attend_info,
            'startTime' => Carbon::make($cast_attend->start_time)->toIso8601String(),
            'endTime' => Carbon::make($cast_attend->end_time)->toIso8601String(),
        ];
    }
}
