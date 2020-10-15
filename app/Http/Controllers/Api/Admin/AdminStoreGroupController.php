<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\StoreGroup;
use Illuminate\Http\Request;

class AdminStoreGroupController
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $store_groups = StoreGroup::query()->paginate(self::_PAGINATION_COUNT);
        $mapped_store_groups = collect($store_groups->items())->map(function (StoreGroup $store_group) {
            return $store_group->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'storeGroups' => $mapped_store_groups,
            'pageCount' => $store_groups->lastPage(),
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
