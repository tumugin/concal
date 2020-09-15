<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $userCount = User::count();
        $users = User::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $users_result = collect($users)->map(function (User $user) {
            return $user->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'users' => $users_result,
            'pageCount' => floor($userCount / self::_PAGINATION_COUNT),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email:rfc',
            'userPrivilege' => 'required|string',
        ]);
        User::createUser(
            $request->post('userName'),
            $request->post('name'),
            $request->post('password'),
            $request->post('email'),
            $request->post('userPrivilege')
        );
        return [
            'success' => true,
        ];
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'user' => 'required|integer',
        ]);
        $user = User::whereId($request->query('user'))->first();
        if ($user === null) {
            return response([
                'error' => 'User not found.',
            ])->setStatusCode(404);
        }
        $user->delete();
        return [
            'success' => true,
        ];
    }

    public function update(Request $request)
    {
        $request->validate([
            'user' => 'required|integer',
            'userName' => 'string',
            'name' => 'string',
            'password' => 'string',
            'email' => 'email:rfc',
        ]);
        $user = User::whereId($request->query('user'))->first();
        if ($user === null) {
            return response([
                'error' => 'User not found.',
            ])->setStatusCode(404);
        }
        $user->updateUserInfo([
            'user_name' => $request->post('userName'),
            'name' => $request->post('name'),
            'password' => $request->post('password'),
            'email' => $request->post('email'),
        ]);
        return [
            'success' => true,
        ];
    }

    public function show(Request $request)
    {
        $request->validate([
            'user' => 'required|integer',
        ]);
        $user = User::whereId($request->query('user'))->first();
        if ($user === null) {
            return response([
                'error' => 'User not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'user' => $user->getAdminAttributes(),
        ];
    }
}
