<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCastAttend;
use App\Http\Requests\Admin\UpdateCastAttend;
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
        $attends_result = $attends->map(function (CastAttend $cast_attend) {
            $store = $cast_attend->store;
            return collect($cast_attend->getAdminAttributes())->merge([
                'store' => $store ? $store->getAdminAttributes() : null,
            ]);
        });
        return [
            'success' => true,
            'attends' => $attends_result,
        ];
    }

    public function show(CastAttend $cast_attend)
    {
        $store = $cast_attend->store()->first();
        return [
            'success' => true,
            'attend' => collect($cast_attend->getAdminAttributes())
                ->merge([
                    'storeName' => $store->store_name,
                    'groupId' => $store->store_group_id,
                    'groupName' => $store->storeGroup()->first()->group_name,
                ]),
        ];
    }

    public function store(Cast $cast, StoreCastAttend $request)
    {
        $cast_attend = new CastAttend([
            'cast_id' => $cast->id,
            'store_id' => $request->post('storeId'),
            'added_by_user_id' => AdminUserAuthService::getCurrentUser()->id,
            'start_time' => $request->post('startTime'),
            'end_time' => $request->post('endTime'),
            'attend_info' => $request->post('attendInfo') ?? '',
        ]);
        $cast_attend->save();
        return [
            'success' => true,
            'id' => $cast_attend->id,
        ];
    }

    public function update(UpdateCastAttend $request, CastAttend $cast_attend)
    {
        $cast_attend->update([
            'store_id' => $request->post('storeId'),
            'added_by_user_id' => AdminUserAuthService::getCurrentUser()->id,
            'start_time' => $request->post('startTime'),
            'end_time' => $request->post('endTime'),
            'attend_info' => $request->post('attendInfo') ?? '',
        ]);
        return [
            'success' => true,
        ];
    }

    public function destroy(CastAttend $attend)
    {
        $attend->delete();
        return [
            'success' => true,
        ];
    }
}
