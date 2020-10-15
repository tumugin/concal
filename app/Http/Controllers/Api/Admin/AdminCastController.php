<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Store;
use Illuminate\Http\Request;

class AdminCastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $casts = Cast::with('stores')->paginate(self::_PAGINATION_COUNT);
        $casts_result = collect($casts->items())->map(fn(Cast $cast) => collect($cast->getAdminAttributes())->merge(
            [
                'stores' => $cast->stores->map(function (Store $store) {
                    return $store->getAdminAttributes();
                }),
            ])
        );
        return [
            'success' => true,
            'casts' => $casts_result,
            'pageCount' => $casts->lastPage(),
        ];
    }

    public function show(Cast $cast)
    {
        return [
            'success' => true,
            'cast' => collect($cast->getAdminAttributes())->merge([
                'stores' => $cast
                    ->stores()
                    ->get()
                    ->map(fn(Store $store) => $store->getAdminAttributes())
            ]),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
        ]);
        Cast::addCast([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription') ?? '',
            'cast_color' => $request->post('castColor'),
        ]);
        return [
            'success' => true,
        ];
    }

    public function update(Request $request, Cast $cast)
    {
        $request->validate([
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
            'storeIds' => 'nullable|string',
            'castDisabled' => 'nullable|string',
        ]);
        $cast->updateCast([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription') ?? '',
            'cast_color' => $request->post('castColor'),
            'cast_disabled' => $request->post('castDisabled') === 'true',
        ]);
        if ($request->has('storeIds')) {
            $comma_separated_store_ids = $request->post('storeIds');
            $store_ids = collect(
                $comma_separated_store_ids !== null ? explode(',', $comma_separated_store_ids) : []
            )
                ->filter(fn($value) => is_numeric($value))
                ->map(fn($value) => (int)$value)
                ->all();
            $cast->updateEnrolledStoresByIds($store_ids);
        }
        return [
            'success' => true,
        ];
    }

    public function destroy(Cast $cast)
    {
        $cast->delete();
        return [
            'success' => true,
        ];
    }
}
