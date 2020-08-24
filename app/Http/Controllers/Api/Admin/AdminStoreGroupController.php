<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\StoreGroup;
use Illuminate\Http\Request;

class AdminStoreGroupController
{
    private const _PAGINATION_COUNT = 10;

    public function getAllStoreGroups(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
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
        ];
    }

    public function getStoreGroup(Request $request)
    {
        $request->validate([
            'storeGroupId' => 'required|integer',
        ]);
        $store_group = StoreGroup::whereId($request->query('storeGroupId'))->first();
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

    public function addStoreGroup(Request $request)
    {
        $request->validate([
            'groupName' => 'required|string',
        ]);
        StoreGroup::addStoreGroup($request->post('groupName'));
        return [
            'success' => true,
        ];
    }

    public function editStoreGroup(Request $request)
    {
        $request->validate([
            'storeGroupId' => 'required|integer',
            'groupName' => 'required|string',
        ]);
        $store_group = StoreGroup::whereId($request->query('storeGroupId'))->first();
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

    public function deleteStoreGroup(Request $request)
    {
        $request->validate([
            'storeGroupId' => 'required|integer',
        ]);
        $store_group = StoreGroup::whereId($request->query('storeGroupId'))->first();
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
