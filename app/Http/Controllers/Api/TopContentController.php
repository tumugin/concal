<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CastAttend;
use App\Models\StoreGroup;

class TopContentController extends Controller
{
    public function index()
    {
        $store_groups = StoreGroup::query()
            ->with('stores')
            ->limit(10)
            ->get()
            ->map(fn($item) => collect($item->getUserAttributes())
                ->merge([
                    'stores' => $item->stores->map(fn($store) => $store->getUserAttributes()),
                ])
            );
        $recent_updated_attends = CastAttend::query()
            ->with('store')
            ->with('store.storeGroup')
            ->with('cast')
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(fn($item) => collect($item->getUserAttributes())
                ->merge([
                    'cast' => $item->cast->getUserAttributes(),
                    'store' => $item->store->getUserAttributes(),
                    'storeGroup' => $item->store->storeGroup->getUserAttributes(),
                ])
            );
        return [
            'success' => true,
            'data' => [
                'storeGroups' => $store_groups,
                'recentUpdatedAttends' => $recent_updated_attends,
            ]
        ];
    }
}
