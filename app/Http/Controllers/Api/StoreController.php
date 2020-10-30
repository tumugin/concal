<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Store;

class StoreController extends Controller
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
            'data' => [
                'stores' => $stores_result,
                'pageCount' => $stores->lastPage(),
                'nextPage' => $stores->hasMorePages() ? $stores->currentPage() + 1 : null,
            ],
        ];
    }

    public function show(Store $store)
    {
        $casts = $store->casts()->with('recentCastAttend');
        $store_info = collect($store->getUserAttributes())
            ->merge(
                [
                    'storeGroup' => $store->storeGroup()->first()->getUserAttributes(),
                    'casts' => $casts->get()->map(
                        function (Cast $cast) {
                            $recent_cast_attend = $cast->recentCastAttend->first();
                            return collect($cast->getUserAttributes())->merge([
                                'recentAttend' => $recent_cast_attend ? $recent_cast_attend->getUserAttributes() : null,
                            ]);
                        }
                    )
                ]
            );
        return [
            'success' => true,
            'data' => [
                'store' => $store_info,
            ],
        ];
    }
}
