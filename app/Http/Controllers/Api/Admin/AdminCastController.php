<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCast;
use App\Http\Requests\Admin\UpdateCast;
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

    public function store(StoreCast $request)
    {
        $request->validate();
        $cast = new Cast([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription') ?? '',
            'cast_color' => $request->post('castColor'),
        ]);
        $cast->save();
        return [
            'success' => true,
            'id' => $cast->id,
        ];
    }

    public function update(UpdateCast $request, Cast $cast)
    {
        $request->validate();
        $cast->update([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription') ?? '',
            'cast_color' => $request->post('castColor'),
            'cast_disabled' => $request->post('castDisabled') === 'true',
        ]);
        if ($request->has('storeIds')) {
            $cast->stores()->sync($request->storeIds);
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
