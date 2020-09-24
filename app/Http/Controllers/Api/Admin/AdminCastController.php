<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Store;
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
        $casts = Cast::with('stores')
            ->skip(self::_PAGINATION_COUNT * ($page - 1))
            ->take(self::_PAGINATION_COUNT)
            ->get();
        $casts_result = $casts->map(function (Cast $cast) {
            return [
                ...$cast->getAdminAttributes(),
                'stores' => $cast->stores()->get()->map(function (Store $store) {
                    return $store->getAdminAttributes();
                }),
            ];
        })->all();
        return [
            'success' => true,
            'casts' => $casts_result,
            'pageCount' => ceil($casts_count / self::_PAGINATION_COUNT),
        ];
    }

    public function show(Cast $cast)
    {
        return [
            'success' => true,
            'cast' => [
                ...$cast->getAdminAttributes(),
                'stores' => $cast->stores()->get()->map(function (Store $store) {
                    return $store->getAdminAttributes();
                }),
            ],
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

    public function update(Request $request, Cast $cast)
    {
        $request->validate([
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => 'nullable|string',
        ]);
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

    public function destroy(Cast $cast)
    {
        $cast->delete();
        return [
            'success' => true,
        ];
    }
}
