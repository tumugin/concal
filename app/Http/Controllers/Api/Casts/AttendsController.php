<?php

namespace App\Http\Controllers\Api\Casts;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\CastAttend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttendsController extends Controller
{
    public function index(Cast $cast, Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);
        $attends = CastAttend::whereCastId($cast->id)
            ->where(
                fn(Builder $query) => $query->whereBetween(
                    'start_time',
                    [$request->get('startDate'), $request->get('endDate')],
                )->whereBetween(
                    'end_time',
                    [$request->get('startDate'), $request->get('endDate')],
                    'or',
                )
            )
            ->with('store')
            ->get();
        $mapped_attends = $attends->map(
            fn(CastAttend $cast_attend) => collect($cast_attend->getUserAttributes())
                ->merge([
                    'store' => $cast_attend->store->getUserAttributes(),
                ])
        );
        return [
            'success' => true,
            'data' => [
                'attends' => $mapped_attends,
            ]
        ];
    }
}
