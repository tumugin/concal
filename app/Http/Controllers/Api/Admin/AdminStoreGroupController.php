<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\StoreStoreGroup;
use App\Http\Requests\Admin\UpdateStoreGroup;
use App\Models\StoreGroup;

class AdminStoreGroupController
{
    private const _PAGINATION_COUNT = 20;

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
        return [
            'success' => true,
            'storeGroup' => $group->getAdminAttributes(),
        ];
    }

    public function store(StoreStoreGroup $request)
    {
        $store_group = new StoreGroup([
            'group_name' => $request->post('groupName'),
        ]);
        $store_group->save();
        return [
            'success' => true,
            'id' => $store_group->id,
        ];
    }

    public function update(UpdateStoreGroup $request, StoreGroup $group)
    {
        $group->update([
            'group_name' => $request->post('groupName'),
        ]);
        $group->save();
        return [
            'success' => true,
        ];
    }

    public function destroy(StoreGroup $group)
    {
        $group->delete();
        return [
            'success' => true,
        ];
    }
}
