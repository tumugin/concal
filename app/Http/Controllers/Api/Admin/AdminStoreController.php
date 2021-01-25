<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStore;
use App\Http\Requests\Admin\UpdateStore;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\StoreIndexTransformer;
use App\Http\Transformers\Api\Admin\StoreShowTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\Cast;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    private const _PAGINATION_COUNT = 20;

    public function index(Request $request)
    {
        $store_group_id = $request->query('storeGroupId');
        $stores = Store::with('storeGroup');
        if ($store_group_id !== null) {
            $stores = $stores->whereHas(
                'storeGroup',
                fn(Builder $query) => $query->where('id', '=', $store_group_id)
            );
        }
        $stores = $stores->paginate(self::_PAGINATION_COUNT);
        return fractal($stores, new StoreIndexTransformer, new DefaultSerializer)
            ->withResourceName('stores')
            ->toArray();
    }

    public function store(StoreStore $request)
    {
        $store = new Store($request->toValueObject());
        $store->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->addMeta([
                'id' => $store->id,
            ])
            ->toArray();
    }

    public function update(UpdateStore $request, Store $store)
    {
        $store->update($request->toValueObject());
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function show(Store $store)
    {
        return fractal($store, new StoreShowTransformer, new DefaultSerializer)
            ->withResourceName('store')
            ->toArray();
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }
}
