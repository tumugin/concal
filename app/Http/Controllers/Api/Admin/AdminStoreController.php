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

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $store_count = Store::count();
        $stores = Store::with('storeGroup')
            ->skip(self::_PAGINATION_COUNT * ($page - 1))
            ->take(self::_PAGINATION_COUNT)
            ->get();
        $stores_result = $stores->map(function (Store $store) {
            return collect($store->getAdminAttributes())
                ->merge([
                    'storeGroup' => $store->storeGroup()->first()->getAdminAttributes()
                ]);
        })->all();
        return [
            'success' => true,
            'stores' => $stores_result,
            'pageCount' => ceil($store_count / self::_PAGINATION_COUNT),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'storeName' => 'required|string',
            'storeGroupId' => 'required|number',
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
            'storeGroupId' => 'required|number',
        ]);
        $store_group = StoreGroup::whereId($request->post('storeGroupId'))->firstOrFail();
        $store->updateStore($request->post('storeName'), $store_group);
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

    public function destroy(Request $request, Store $store)
    {
        $request->validate([
            'store' => 'required|integer',
        ]);
        $store->delete();
        return [
            'success' => true,
        ];
    }
}
