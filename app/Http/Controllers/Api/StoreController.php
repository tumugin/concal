<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\StoreIndexTransformer;
use App\Http\Transformers\Api\StoreShowTransformer;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        $casts = $store->casts()->active()->with('castAttends', fn(HasMany $query) => $query
            ->where('store_id', '=', $store->id)
            ->where('end_time', '>', Carbon::now())
            ->orderBy('end_time')
        );
        $result = fractal(
            $casts,
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
