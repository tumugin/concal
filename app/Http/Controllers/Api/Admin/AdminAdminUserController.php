<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUser;
use App\Http\Requests\Admin\UpdateAdminUser;
use App\Models\AdminUser;

class AdminAdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 20;

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
        $user = new AdminUser($request->validated());
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
        $admin_user->update($request->validated());
        return [
            'success' => true,
        ];
    }

    public function show(AdminUser $admin_user)
    {
        return [
            'success' => true,
            'user' => $admin_user->getAdminAttributes(),
        ];
    }
}
