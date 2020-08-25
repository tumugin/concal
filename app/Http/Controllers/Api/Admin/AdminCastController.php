<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Http\Request;

class AdminCastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function getAllCasts(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $casts = Cast::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $casts_result = collect($casts)->map(function (Cast $cast) {
            return [
                'id' => $cast->id,
                'castName' => $cast->cast_name,
                'castShortName' => $cast->cast_short_name,
                'castTwitterId' => $cast->cast_twitter_id,
                'castDescription' => $cast->cast_description,
                'castDisabled' => $cast->cast_disabled === 1,
            ];
        });
        return [
            'success' => true,
            'casts' => $casts_result,
        ];
    }

    public function getCast(Request $request)
    {
        $request->validate([
            'castId' => 'required|integer',
        ]);
        $cast = Cast::whereId($request->query('castId'))->first();
        if ($cast === null) {
            return response([
                'error' => 'Cast not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'castInfo' => [
                'id' => $cast->id,
                'castName' => $cast->cast_name,
                'castShortName' => $cast->cast_short_name,
                'castTwitterId' => $cast->cast_twitter_id,
                'castDescription' => $cast->cast_description,
                'castDisabled' => $cast->cast_disabled === 1,
            ]
        ];
    }

    public function addCast(Request $request)
    {
        $request->validate([
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
        ]);
        Cast::addCast([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription'),
            'cast_color' => $request->post('cast_color'),
        ]);
        return [
            'success' => true,
        ];
    }

    public function editCast(Request $request)
    {
        $request->validate([
            'castId' => 'required|integer',
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
        ]);
        $cast = Cast::whereId($request->query('castId'))->first();
        if ($cast === null) {
            return response([
                'error' => 'Cast not found.',
            ])->setStatusCode(404);
        }
        $cast->editStore([
            'cast_name' => $request->post('castName'),
            'cast_short_name' => $request->post('castShortName'),
            'cast_twitter_id' => $request->post('castTwitterId'),
            'cast_description' => $request->post('castDescription'),
            'cast_color' => $request->post('cast_color'),
        ]);
        return [
            'success' => true,
        ];
    }
}
