<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastAttend;
use App\Services\UserAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminCastAttendController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index(Request $request)
    {
        $request->validate([
            'cast' => 'required|integer',
            'page' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
        ]);
        $page = (int)$request->get('page');
        $attends = CastAttend::whereCastId($request->query('cast'))
            ->whereBetween(
                'start_time',
                [$request->get('startTime'), $request->get('endTime')]
            )
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->with('store')
            ->get();
        $attends_result = collect($attends)->map(function (CastAttend $cast_attend) {
            return $cast_attend->getAdminAttributes();
        });
        return [
            'success' => true,
            'attends' => $attends_result,
        ];
    }

    public function show(Request $request)
    {
        $request->validate([
            'attend' => 'required|integer',
        ]);
        $attend_id = $request->query('attend');
        $cast_attend = CastAttend::whereId($attend_id)
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
                ...$cast_attend->getAdminAttributes(),
                'storeName' => $cast_attend->store->store_name,
                'groupId' => $cast_attend->store->store_group_id,
                'groupName' => $cast_attend->store->getBelongingStoreGroup()->group_name,
            ],
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

    public function update(Request $request)
    {
        $request->validate([
            'attend' => 'required|integer',
            'storeId' => 'required|integer',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
            'attendInfo' => 'nullable|string',
        ]);
        $cast_attend = CastAttend::whereId($request->query('attend'))->first();
        if ($cast_attend === null) {
            return response([
                'error' => 'Cast attend not found.',
            ])->setStatusCode(404);
        }
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

    public function destroy(Request $request)
    {
        $request->validate([
            'attend' => 'required|integer',
        ]);
        $cast_attend = CastAttend::whereId($request->query('attend'))->first();
        if ($cast_attend === null) {
            return response([
                'error' => 'Cast attend not found.',
            ])->setStatusCode(404);
        }
        $cast_attend->delete();
        return [
            'success' => true,
        ];
    }
}