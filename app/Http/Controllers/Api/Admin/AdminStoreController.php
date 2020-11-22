<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStore;
use App\Http\Requests\Admin\UpdateStore;
use App\Models\Cast;
use App\Models\Store;
use App\Models\StoreGroup;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $stores = Store::with('storeGroup')->paginate(self::_PAGINATION_COUNT);
        $stores_result = collect($stores->items())->map(function (Store $store) {
            return collect($store->getAdminAttributes())
                ->merge([
                    'storeGroup' => $store->storeGroup->getAdminAttributes()
                ]);
        })->all();
        return [
            'success' => true,
            'stores' => $stores_result,
            'pageCount' => $stores->lastPage(),
        ];
    }

    public function store(StoreStore $request)
    {
        $request->validate();
        $store = new Store([
            'store_name' => $request->post('storeName'),
            'store_group_id' => $request->post('storeGroupId'),
            'store_disabled' => false,
        ]);
        $store->save();
        return [
            'success' => true,
            'id' => $store->id,
        ];
    }

    public function update(UpdateStore $request, Store $store)
    {
        $request->validate();
        $store->update([
            'store_name' => $request->post('storeName'),
            'store_group_id' => $request->post('storeGroupId'),
            'store_disabled' => $request->post('storeDisabled') === 'true',
        ]);
        return [
            'success' => true,
        ];
    }

    public function show(Store $store)
    {
        $store_info = collect($store->getAdminAttributes())
            ->merge(
                [
                    'storeGroup' => $store->storeGroup()->first()->getAdminAttributes(),
                    'casts' => $store->casts()->get()->map(
                        fn(Cast $cast) => $cast->getAdminAttributes()
                    )
                ]
            );
        return [
            'success' => true,
            'store' => $store_info,
        ];
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return [
            'success' => true,
        ];
    }
}
