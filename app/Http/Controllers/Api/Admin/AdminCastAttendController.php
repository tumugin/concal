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
        return [
            'success' => true,
            'id' => $cast_attend->id,
        ];
    }

    public function update(UpdateCastAttend $request, CastAttend $cast_attend, AdminUserAuthService $admin_user_auth_service)
    {
        $cast_attend->update(array_merge(
            $request->toValueObject(),
            [
                'added_by_user_id' => $admin_user_auth_service->getCurrentUser()->id,
            ]
        ));
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
