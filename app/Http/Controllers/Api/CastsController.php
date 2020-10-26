<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Store;

class CastsController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $casts = Cast::with('stores')->paginate(self::_PAGINATION_COUNT);
        $casts_result = collect($casts->items())->map(fn(Cast $cast) => collect($cast->getUserAttributes())->merge(
            [
                'stores' => $cast->stores->map(function (Store $store) {
                    return $store->getUserAttributes();
                }),
            ])
        );
        return [
            'success' => true,
            'casts' => $casts_result,
            'pageCount' => $casts->lastPage(),
            'nextPage' => $casts->hasMorePages() ? $casts->currentPage() + 1 : null,
        ];
    }

    public function show(Cast $cast)
    {
        return [
            'success' => true,
            'cast' => collect($cast->getUserAttributes())->merge([
                'stores' => $cast
                    ->stores()
                    ->get()
                    ->map(fn(Store $store) => $store->getUserAttributes())
            ]),
        ];
    }
}
