<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\CastIndexTransformer;
use App\Http\Transformers\Api\CastShowTransformer;
use App\Models\Cast;

class CastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $casts = Cast::active()
            ->with('stores')
            ->paginate(self::_PAGINATION_COUNT);
        $result = fractal(
            $casts,
            new CastIndexTransformer,
            new DefaultSerializer
        )
            ->withResourceName('casts')
            ->toArray();
        return [
            'data' => $result,
        ];
    }

    public function show(Cast $cast)
    {
        $result = fractal(
            $cast,
            new CastShowTransformer,
            new DefaultSerializer
        )
            ->withResourceName('cast')
            ->toArray();
        return [
            'data' => $result,
        ];
    }
}
