<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastAttend;
use Illuminate\Http\Request;

class AdminCastAttendController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function getAttends(Request $request)
    {
        $request->validate([
            'castId' => 'required|integer',
            'page' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
        ]);
        $page = (int)$request->get('page');
        $attends = CastAttend::whereCastId($request->get('castId'))
            ->whereBetween(
                'start_time',
                [$request->get('startTime'), $request->get('endTime')]
            )
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->with('store')
            ->get();
        $attends_result = collect($attends)->map(function (CastAttend $cast_attend) {
            return [
                'id' => $cast_attend->id,
                'storeId' => $cast_attend->store_id,
                'storeName' => $cast_attend->store->store_name,
                'startTime' => $cast_attend->start_time,
                'endTime' => $cast_attend->end_time,
                'attendInfo' => $cast_attend->attend_info,
                'addedByUserId' => $cast_attend->added_by_user_id,
            ];
        });
        return [
            'success' => true,
            'attends' => $attends_result,
        ];
    }

    public function getAttend(Request $request)
    {
        $request->validate([
            'attendId' => 'required|integer',
        ]);
        $attend_id = $request->get('attendid');
        $cast_attend = CastAttend::whereCastAttendId($attend_id)
            ->with('store')
            ->first();
        if ($cast_attend === null) {
            return response([
                'error' => 'Cast attend not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'attend' => [
                'id' => $cast_attend->id,
                'storeId' => $cast_attend->store_id,
                'storeName' => $cast_attend->store->store_name,
                'groupId' => $cast_attend->store->store_group_id,
                'groupName' => $cast_attend->store->getBelongingStoreGroup()->group_name,
                'startTime' => $cast_attend->start_time,
                'endTime' => $cast_attend->end_time,
                'attendInfo' => $cast_attend->attend_info,
                'addedByUserId' => $cast_attend->added_by_user_id,
            ],
        ];
    }

    public function addAttend(Request $request)
    {
        $request->validate([
            'castId' => 'required|integer',
            'storeId' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
            'attendInfo' => 'nullable|string',
        ]);
    }
}
