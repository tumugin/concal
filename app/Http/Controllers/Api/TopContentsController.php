<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreGroup;

class TopContentsController extends Controller
{
    const _PAGENATION_PER = 10;

    public function index()
    {
        $store_groups = StoreGroup::query()
            ->with('stores')
            ->paginate(self::_PAGENATION_PER);
        $mapped_store_groups = collect($store_groups->items())
            ->map(fn($item) => collect($item->getUserAttributes())
                ->merge([
                    'stores' => $item->stores->map(fn($store) => $store->getUserAttributes())
                ])
            );
        return [
            'success' => true,
            'data' => [
                'storeGroups' => $mapped_store_groups,
                'nextPage' => $store_groups->hasMorePages() ? $store_groups->currentPage() + 1 : null,
            ]
        ];
    }
}
