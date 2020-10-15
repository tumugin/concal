<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $users = User::query()->paginate(self::_PAGINATION_COUNT);
        $users_result = collect($users->items())->map(function (User $user) {
            return $user->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'users' => $users_result,
            'pageCount' => $users->lastPage(),
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

    public function destroy(User $user)
    {
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

    public function update(Request $request, User $user)
    {
        $request->validate([
            'userName' => 'string',
            'name' => 'string',
            'password' => 'string',
            'email' => 'email:rfc',
            'user_privilege' => 'string',
        ]);
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
            'user_privilege' => $request->post('userPrivilege')
        ]);
        return [
            'success' => true,
        ];
    }

    public function show(User $user)
    {
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
