<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Http\Request;

class AdminCastController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $casts_count = Cast::count();
        $casts = Cast::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $casts_result = collect($casts)->map(function (Cast $cast) {
            return $cast->getAdminAttributes();
        });
        return [
            'success' => true,
            'casts' => $casts_result,
            'pageCount' => ceil($casts_count / self::_PAGINATION_COUNT),
        ];
    }

    public function show(Request $request)
    {
        $request->validate([
            'cast' => 'required|integer',
        ]);
        $cast = Cast::whereId($request->query('cast'))->first();
        if ($cast === null) {
            return response([
                'error' => 'Cast not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'cast' => $cast->getAdminAttributes(),
        ];
    }

    public function store(Request $request)
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

    public function update(Request $request)
    {
        $request->validate([
            'cast' => 'required|integer',
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
        ]);
        $cast = Cast::whereId($request->query('cast'))->first();
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

    public function destroy(Request $request)
    {
        $request->validate([
            'cast' => 'required|integer',
        ]);
        $cast = Cast::whereId($request->query('cast'))->first();
        if ($cast === null) {
            return response([
                'error' => 'Cast not found.',
            ])->setStatusCode(404);
        }
        $cast->delete();
        return [
            'success' => true,
        ];
    }
}
