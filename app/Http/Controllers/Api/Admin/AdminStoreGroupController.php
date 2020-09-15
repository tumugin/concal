<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\StoreGroup;
use Illuminate\Http\Request;

class AdminStoreGroupController
{
    private const _PAGINATION_COUNT = 10;

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $store_groups_count = StoreGroup::count();
        $store_groups = StoreGroup::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $store_groups = collect($store_groups)->map(function (StoreGroup $store_group) {
            return [
                'id' => $store_group->id,
                'groupName' => $store_group->group_name,
            ];
        })->all();
        return [
            'success' => true,
            'storeGroups' => $store_groups,
            'pageCount' => floor($store_groups_count / self::_PAGINATION_COUNT),
        ];
    }

    public function show(Request $request)
    {
        $request->validate([
            'group' => 'required|integer',
        ]);
        $store_group = StoreGroup::whereId($request->query('group'))->first();
        if ($store_group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        $store_group_info = [
            'id' => $store_group->id,
            'groupName' => $store_group->group_name,
        ];
        return [
            'success' => true,
            'storeGroup' => $store_group_info,
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'groupName' => 'required|string',
        ]);
        StoreGroup::addStoreGroup($request->post('groupName'));
        return [
            'success' => true,
        ];
    }

    public function update(Request $request)
    {
        $request->validate([
            'group' => 'required|integer',
            'groupName' => 'required|string',
        ]);
        $store_group = StoreGroup::whereId($request->query('group'))->first();
        if ($store_group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        $store_group->updateStoreInfo($request->post('groupName'));
        return [
            'success' => true,
        ];
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'group' => 'required|integer',
        ]);
        $store_group = StoreGroup::whereId($request->query('group'))->first();
        if ($store_group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        $store_group->deleteStoreGroup();
        return [
            'success' => true,
        ];
    }
}
