<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUser;
use App\Http\Requests\Admin\UpdateAdminUser;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function index()
    {
        $users = AdminUser::query()->paginate(self::_PAGINATION_COUNT);
        $users_result = collect($users->items())->map(function (AdminUser $user) {
            return $user->getAdminAttributes();
        })->all();
        return [
            'success' => true,
            'users' => $users_result,
            'pageCount' => $users->lastPage(),
        ];
    }

    public function store(StoreAdminUser $request)
    {
        $request->validate();
        $user = new AdminUser([
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

    public function destroy(AdminUser $admin_user)
    {
        $admin_user->delete();
        return [
            'success' => true,
        ];
    }

    public function update(UpdateAdminUser $request, AdminUser $admin_user)
    {
        $request->validate();
        if ($request->post('userName') !== null) {
            $admin_user->user_name = $request->post('userName');
        }
        if ($request->post('name') !== null) {
            $admin_user->name = $request->post('name');
        }
        if ($request->post('password') !== null) {
            $admin_user->password = Hash::make($request->post('password'));
        }
        if ($request->post('email') !== null) {
            $admin_user->email = $request->post('email');
        }
        if ($request->post('userPrivilege') !== null) {
            $admin_user->user_privilege = $request->post('userPrivilege');
        }
        $admin_user->save();
        return [
            'success' => true,
        ];
    }

    public function show(AdminUser $admin_user)
    {
        if ($admin_user === null) {
            return response([
                'error' => 'User not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'user' => $admin_user->getAdminAttributes(),
        ];
    }
}
