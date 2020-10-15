<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $request->validate([
            'storeName' => 'required|string',
            'storeGroupId' => 'required|integer',
        ]);
        $store_group = StoreGroup::whereId($request->post('storeGroupId'))->firstOrFail();
        Store::createStore(
            $request->post('storeName'),
            $store_group
        );
        return [
            'success' => true,
        ];
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'storeName' => 'required|string',
            'storeGroupId' => 'required|integer',
            'storeDisabled' => 'required|string'
        ]);
        $store_group = StoreGroup::whereId($request->post('storeGroupId'))->firstOrFail();
        $store->updateStore($request->post('storeName'), $store_group);
        $store->setStoreClosed($request->post('storeDisabled') === 'true');
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
