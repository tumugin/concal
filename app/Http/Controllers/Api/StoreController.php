<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\StoreIndexTransformer;
use App\Http\Transformers\Api\StoreShowTransformer;
use App\Models\Store;

class StoreController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $stores = Store::active()->with('storeGroup')->paginate(self::_PAGINATION_COUNT);
        $result = fractal(
            $stores,
            new StoreIndexTransformer,
            new DefaultSerializer
        )
            ->withResourceName('stores')
            ->toArray();
        return [
            'data' => $result,
        ];
    }

    public function show(Store $store)
    {
        $result = fractal(
            $store,
            new StoreShowTransformer,
            new DefaultSerializer
        )
            ->withResourceName('store')
            ->toArray();
        return [
            'data' => $result,
        ];
    }
}
