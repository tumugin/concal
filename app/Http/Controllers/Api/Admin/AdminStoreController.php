<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreGroup;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function getAllStores(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $stores = Store::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $stores_result = collect($stores)->map(function (Store $store) {
            return [
                'id' => $store->id,
                'storeName' => $store->store_name,
                'storeGroupId' => $store->store_group_id,
                'isDisabled' => $store->store_disabled,
            ];
        })->all();
        return [
            'success' => true,
            'stores' => $stores_result,
        ];
    }

    public function addStore(Request $request)
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

    public function editStore(Request $request)
    {
        $request->validate([
            'storeId' => 'required|number',
            'storeName' => 'required|string',
            'storeGroupId' => 'required|number',
        ]);
        $store = Store::whereId($request->query('storeId'))->first();
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

    public function getStore(Request $request)
    {
        $request->validate([
            'storeId' => 'required|integer',
        ]);
        $store = Store::whereId($request->query('storeId'))->first();
        if ($store === null) {
            return response([
                'error' => 'Store not found.',
            ])->setStatusCode(404);
        }
        $store_info = [
            'id' => $store->id,
            'storeName' => $store->store_name,
            'storeGroupId' => $store->store_group_id,
            'isDisabled' => $store->store_disabled,
        ];
        return [
            'success' => true,
            'store' => $store_info,
        ];
    }

    public function deleteStore(Request $request)
    {
        $request->validate([
            'storeId' => 'required|integer',
        ]);
        $store = Store::whereId($request->query('storeId'))->first();
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
