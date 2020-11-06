<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\CastAttend;
use App\Models\Store;
use Carbon\Carbon;

class CastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $casts = Cast::active()
            ->with('stores')
            ->paginate(self::_PAGINATION_COUNT);
        $casts_result = collect($casts->items())->map(fn(Cast $cast) => collect($cast->getUserAttributes())->merge(
            [
                'stores' => $cast->stores->active->map(function (Store $store) {
                    return $store->getUserAttributes();
                }),
            ])
        );
        return [
            'success' => true,
            'data' => [
                'casts' => $casts_result,
                'pageCount' => $casts->lastPage(),
                'nextPage' => $casts->hasMorePages() ? $casts->currentPage() + 1 : null,
            ],
        ];
    }

    public function show(Cast $cast)
    {
        $recent_cast_attends = $cast->castAttends()
            ->with('store')
            ->where('end_time', '>', Carbon::now())
            ->orderBy('end_time')
            ->limit(10)
            ->get();
        $stores = $cast
            ->stores()
            ->active()
            ->with('storeGroup')
            ->get();
        return [
            'success' => true,
            'data' => [
                'cast' => collect($cast->getUserAttributes())->merge([
                    'stores' => $stores
                        ->map(fn(Store $store) => collect($store->getUserAttributes())
                            ->merge([
                                'storeGroup' => $store->storeGroup->getUserAttributes()
                            ])
                        ),
                    'recentAttends' => $recent_cast_attends->map(
                        fn(CastAttend $attend) => collect($attend->getUserAttributes())->merge([
                            'store' => $attend->store->getUserAttributes()
                        ])
                    ),
                ]),
            ],
        ];
    }
}
