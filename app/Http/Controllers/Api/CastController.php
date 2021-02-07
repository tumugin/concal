<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Serializers\DataSerializer;
use App\Http\Transformers\Api\CastIndexTransformer;
use App\Http\Transformers\Api\CastShowTransformer;
use App\Models\Cast;
use Carbon\Carbon;

class CastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $casts = Cast::active()
            ->with('stores')
            ->paginate(self::_PAGINATION_COUNT);
        return fractal($casts, new CastIndexTransformer, new DataSerializer);
    }

    public function show(Cast $cast)
    {
        $recent_cast_attends = $cast->castAttends
            ->with('store')
            ->where('end_time', '>', Carbon::now())
            ->orderBy('end_time')
            ->limit(10);
        $stores = $cast
            ->stores
            ->active
            ->with('storeGroup');
        return fractal(
            [
                'cast' => $cast,
                'stores' => $stores,
                'recent_attends' => $recent_cast_attends
            ],
            new CastShowTransformer,
            new DataSerializer
        );
    }
}
