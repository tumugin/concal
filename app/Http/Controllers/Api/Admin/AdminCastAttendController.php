<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastAttend;
use App\Models\Store;
use App\Models\StoreGroup;
use App\Services\UserAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminCastAttendController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'startTime' => 'required|date',
            'endTime' => 'required|date',
        ]);
        $attends = CastAttend::whereCastId($request->query('cast'))
            ->whereBetween(
                'start_time',
                [$request->get('startTime'), $request->get('endTime')],
                'or'
            )
            ->whereBetween(
                'end_time',
                [$request->get('startTime'), $request->get('endTime')],
                'or'
            )
            ->with(Store::class)
            ->with('store.storeGroup')
            ->get();
        $attends_result = $attends->map(function (CastAttend $cast_attend) {
            $store = $cast_attend->store()->first();
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
        if ($cast_attend === null) {
            return response([
                'error' => 'Cast attend not found.',
            ])->setStatusCode(404);
        }
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

    public function store(Request $request)
    {
        $request->validate([
            'cast' => 'required|integer',
            'storeId' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
            'attendInfo' => 'nullable|string',
        ]);
        CastAttend::addAttendance(
            (int)$request->query('cast'),
            (int)$request->post('storeId'),
            UserAuthService::getCurrentUser('api')->id,
            Carbon::parse($request->post('startTime')),
            Carbon::parse($request->post('endTime')),
            $request->post('attendInfo') ?? ''
        );
        return [
            'success' => true,
        ];
    }

    public function update(Request $request, CastAttend $cast_attend)
    {
        $request->validate([
            'storeId' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
            'attendInfo' => 'nullable|string',
        ]);
        $cast_attend->updateAttendance(
            (int)$request->post('storeId'),
            UserAuthService::getCurrentUser('api')->id,
            Carbon::parse($request->post('startTime')),
            Carbon::parse($request->post('endTime')),
            $request->post('attendInfo') ?? ''
        );
        return [
            'success' => true,
        ];
    }

    public function destroy(CastAttend $cast_attend)
    {
        $cast_attend->delete();
        return [
            'success' => true,
        ];
    }
}
