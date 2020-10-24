<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Store;

class StoresController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $stores = Store::with('storeGroup')->paginate(self::_PAGINATION_COUNT);
        $stores_result = collect($stores->items())->map(function (Store $store) {
            return collect($store->getUserAttributes())
                ->merge([
                    'storeGroup' => $store->storeGroup->getUserAttributes()
                ]);
        })->all();
        return [
            'success' => true,
            'stores' => $stores_result,
            'pageCount' => $stores->lastPage(),
            'nextPage' => $stores->hasMorePages() ? $stores->currentPage() + 1 : null,
        ];
    }

    public function show(Store $store)
    {
        $store_info = collect($store->getUserAttributes())
            ->merge(
                [
                    'storeGroup' => $store->storeGroup()->first()->getUserAttributes(),
                    'casts' => $store->casts()->get()->map(
                        fn(Cast $cast) => $cast->getUserAttributes()
                    )
                ]
            );
        return [
            'success' => true,
            'store' => $store_info,
        ];
    }
}
