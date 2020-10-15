<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreGroup;

class TopContentsController extends Controller
{
    public function index()
    {
        $store_groups = StoreGroup::query()
            ->with('stores')
            ->limit(10);
        $mapped_store_groups = $store_groups
            ->get()
            ->map(fn($item) => collect($item->getUserAttributes())
                ->merge([
                    'stores' => $item->stores->map(fn($store) => $store->getUserAttributes())
                ])
            );
        return [
            'success' => true,
            'data' => [
                'storeGroups' => $mapped_store_groups,
            ]
        ];
    }
}
