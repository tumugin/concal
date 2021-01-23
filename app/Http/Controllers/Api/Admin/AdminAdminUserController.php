<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUser;
use App\Http\Requests\Admin\UpdateAdminUser;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\AdminUserTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\AdminUser;

class AdminAdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 20;

    public function index()
    {
        $users = AdminUser::query()->paginate(self::_PAGINATION_COUNT);
        return fractal($users, new AdminUserTransformer, new DefaultSerializer)
            ->withResourceName('users')
            ->toArray();
    }

    public function store(StoreAdminUser $request)
    {
        $user = new AdminUser($request->toValueObject());
        $user->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->addMeta([
                'id' => $user->id,
            ])
            ->toArray();
    }

    public function destroy(AdminUser $admin_user)
    {
        $admin_user->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function update(UpdateAdminUser $request, AdminUser $admin_user)
    {
        $admin_user->update($request->toValueObject());
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function show(AdminUser $admin_user)
    {
        return fractal($admin_user, new AdminUserTransformer, new DefaultSerializer)
            ->withResourceName('user')
            ->toArray();
    }
}
