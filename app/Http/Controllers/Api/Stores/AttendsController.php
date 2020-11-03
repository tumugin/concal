<?php

namespace App\Http\Controllers\Api\Stores;

use App\Http\Controllers\Controller;
use App\Models\CastAttend;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttendsController extends Controller
{
    public function index(Store $store, Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);
        $attends = CastAttend::whereStoreId($store->id)
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
            ->where('store_id', '=', $store->id)
            ->with('cast')
            ->get();
        $mapped_attends = $attends->map(
            fn(CastAttend $cast_attend) => collect($cast_attend->getUserAttributes())
                ->merge([
                    'cast' => $cast_attend->cast->getUserAttributes(),
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
