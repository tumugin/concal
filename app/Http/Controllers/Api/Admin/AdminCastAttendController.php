<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCastAttend;
use App\Http\Requests\Admin\UpdateCastAttend;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\CastAttendIndexTransformer;
use App\Http\Transformers\Api\Admin\CastAttendShowTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\Cast;
use App\Models\CastAttend;
use App\Services\AdminUserAuthService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminCastAttendController extends Controller
{
    public function index(Request $request, Cast $cast)
    {
        $request->validate([
            'startTime' => 'required|date',
            'endTime' => 'required|date',
        ]);
        $attends = CastAttend::whereCastId($cast->id)
            ->where(
                fn(Builder $query) => $query->whereBetween(
                    'start_time',
                    [$request->get('startTime'), $request->get('endTime')],
                )->whereBetween(
                    'end_time',
                    [$request->get('startTime'), $request->get('endTime')],
                    'or',
                )
            )
            ->with('store')
            ->with('store.storeGroup')
            ->get();
        return fractal($attends, new CastAttendIndexTransformer, new DefaultSerializer)
            ->withResourceName('attends')
            ->toArray();
    }

    public function show(CastAttend $cast_attend)
    {
        return fractal($cast_attend, new CastAttendShowTransformer, new DefaultSerializer)
            ->withResourceName('attend')
            ->toArray();
    }

    public function store(Cast $cast, StoreCastAttend $request, AdminUserAuthService $admin_user_auth_service)
    {
        $cast_attend = new CastAttend(array_merge(
            $request->toValueObject(),
            [
                'cast_id' => $cast->id,
                'added_by_user_id' => $admin_user_auth_service->getCurrentUser()->id,
            ]
        ));
        $cast_attend->save();
        return fractal($cast_attend->id, new EmptyTransformer, new DefaultSerializer)
            ->withResourceName('id')
            ->toArray();
    }

    public function update(UpdateCastAttend $request, CastAttend $cast_attend, AdminUserAuthService $admin_user_auth_service)
    {
        $cast_attend->update(array_merge(
            $request->toValueObject(),
            [
                'added_by_user_id' => $admin_user_auth_service->getCurrentUser()->id,
            ]
        ));
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function destroy(CastAttend $attend)
    {
        $attend->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }
}
