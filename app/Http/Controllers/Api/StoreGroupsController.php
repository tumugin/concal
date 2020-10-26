<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreGroup;

class StoreGroupsController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $store_groups = StoreGroup::query()->paginate(self::_PAGINATION_COUNT);
        $mapped_store_groups = collect($store_groups->items())->map(function (StoreGroup $store_group) {
            return $store_group->getUserAttributes();
        })->all();
        return [
            'success' => true,
            'storeGroups' => $mapped_store_groups,
            'pageCount' => $store_groups->lastPage(),
            'nextPage' => $store_groups->hasMorePages() ? $store_groups->currentPage() + 1 : null,
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
            'storeGroup' => $group->getUserAttributes(),
        ];
    }
}
