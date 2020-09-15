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
        $stores = Store::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $stores_result = collect($stores)->map(function (Store $store) {
            return $store->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'stores' => $stores_result,
            'pageCount' => floor($store_count / self::_PAGINATION_COUNT),
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

    public function update(Request $request)
    {
        $request->validate([
            'store' => 'required|number',
            'storeName' => 'required|string',
            'storeGroupId' => 'required|number',
        ]);
        $store = Store::whereId($request->query('store'))->first();
        if ($store === null) {
            return response([
                'error' => 'Store not found.',
            ])->setStatusCode(404);
        }
        $store_group = StoreGroup::whereId($request->post('storeGroupId'))->firstOrFail();
        $store->updateStore($request->post('storeName'), $store_group);
        return [
            'success' => true,
        ];
    }

    public function show(Request $request)
    {
        $request->validate([
            'store' => 'required|integer',
        ]);
        $store = Store::whereId($request->query('store'))->first();
        if ($store === null) {
            return response([
                'error' => 'Store not found.',
            ])->setStatusCode(404);
        }
        $store_info = [
            ...$store->getAdminAttributes(),
            'casts' => collect($store->getBelongingCasts()->get()->all())
                ->map(function (Cast $cast) {
                    return $cast->getAdminAttributes();
                }),
        ];
        return [
            'success' => true,
            'store' => $store_info,
        ];
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'store' => 'required|integer',
        ]);
        $store = Store::whereId($request->query('store'))->first();
        if ($store === null) {
            return response([
                'error' => 'Store not found.',
            ])->setStatusCode(404);
        }
        $store->delete();
        return [
            'success' => true,
        ];
    }
}
