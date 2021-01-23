<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCast;
use App\Http\Requests\Admin\UpdateCast;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\CastIndexTransformer;
use App\Http\Transformers\Api\Admin\CastShowTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\Cast;
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
            $casts = $casts->whereHas(
                'stores',
                fn(Builder $query) => $query->where('id', '=', $store_id)
            );
        }
        $casts = $casts->paginate(self::_PAGINATION_COUNT);
        return fractal($casts, new CastIndexTransformer, new DefaultSerializer)
            ->withResourceName('casts')
            ->toArray();
    }

    public function show(Cast $cast)
    {
        return fractal($cast, new CastShowTransformer, new DefaultSerializer)
            ->withResourceName('cast')
            ->toArray();
    }

    public function store(StoreCast $request)
    {
        $cast = new Cast($request->toValueObject());
        $cast->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->addMeta([
                'id' => $cast->id,
            ])
            ->toArray();
    }

    public function update(UpdateCast $request, Cast $cast)
    {
        $cast->update($request->toValueObject());
        if ($request->has('storeIds')) {
            $cast->stores()->sync($request->storeIds);
        }
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function destroy(Cast $cast)
    {
        $cast->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }
}
