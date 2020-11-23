<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function store(StoreUser $request)
    {
        $user = new User([
            'user_name' => $request->post('userName'),
            'name' => $request->post('name'),
            'password' => Hash::make($request->post('password')),
            'email' => $request->post('email'),
            'user_privilege' => $request->post('userPrivilege'),
        ]);
        $user->save();
        return [
            'success' => true,
            'id' => $user->id,
        ];
    }

    public function destroy(User $user)
    {
        $user->delete();
        return [
            'success' => true,
        ];
    }

    public function update(UpdateUser $request, User $user)
    {
        if ($request->post('userName') !== null) {
            $user->user_name = $request->post('userName');
        }
        if ($request->post('name') !== null) {
            $user->name = $request->post('name');
        }
        if ($request->post('password') !== null) {
            $user->password = Hash::make($request->post('password'));
        }
        if ($request->post('email') !== null) {
            $user->email = $request->post('email');
        }
        if ($request->post('userPrivilege') !== null) {
            $user->user_privilege = $request->post('userPrivilege');
        }
        $user->save();
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
