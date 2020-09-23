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
            ->skip(self::_PAGINATION_COUNT * ($page - 1))
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $store_groups = collect($store_groups)->map(function (StoreGroup $store_group) {
            return $store_group->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'storeGroups' => $store_groups,
            'pageCount' => ceil($store_groups_count / self::_PAGINATION_COUNT),
        ];
    }

    public function show(StoreGroup $group)
    {
        if ($group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'storeGroup' => $group->getAdminAttributes(),
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

    public function update(Request $request, StoreGroup $group)
    {
        $request->validate([
            'groupName' => 'required|string',
        ]);
        if ($group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        $group->updateStoreInfo($request->post('groupName'));
        return [
            'success' => true,
        ];
    }

    public function destroy(StoreGroup $group)
    {
        if ($group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        $group->deleteStoreGroup();
        return [
            'success' => true,
        ];
    }
}
