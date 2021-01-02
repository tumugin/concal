<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCast;
use App\Http\Requests\Admin\UpdateCast;
use App\Models\Cast;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminCastController extends Controller
{
    private const _PAGINATION_COUNT = 20;

    public function index(Request $request)
    {
        $store_id = $request->query('storeId');
        $casts = Cast::with(['stores', 'latestCastAttend']);
        if ($store_id !== null) {
            $casts = $casts->whereHas('stores', fn(Builder $query) => $query->where('id', '=', $store_id));
        }
        $casts = $casts->paginate(self::_PAGINATION_COUNT);
        $casts_result = collect($casts->items())->map(fn(Cast $cast) => collect($cast->getAdminAttributes())->merge(
            [
                'stores' => $cast->stores->map(function (Store $store) {
                    return $store->getAdminAttributes();
                }),
                'latestCastAttend' => $cast->latestCastAttend?->getAdminAttributes(),
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
                    ->map(fn(Store $store) => $store->getAdminAttributes()),
                'latestCastAttend' => $cast->latestCastAttend?->getAdminAttributes(),
            ]),
        ];
    }

    public function store(StoreCast $request)
    {
        $cast = new Cast($request->toValueObject());
        $cast->save();
        return [
            'success' => true,
            'id' => $cast->id,
        ];
    }

    public function update(UpdateCast $request, Cast $cast)
    {
        $cast->update($request->toValueObject());
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
