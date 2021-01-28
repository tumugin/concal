<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\UserTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\User;

class AdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 20;

    public function index()
    {
        $users = User::query()->paginate(self::_PAGINATION_COUNT);
        return fractal($users, new UserTransformer, new DefaultSerializer)
            ->withResourceName('users')
            ->toArray();
    }

    public function store(StoreUser $request)
    {
        $user = new User($request->toValueObject());
        $user->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->addMeta([
                'id' => $user->id,
            ])
            ->toArray();
    }

    public function destroy(User $user)
    {
        $user->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->toValueObject());
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function show(User $user)
    {
        return fractal($user, new UserTransformer, new DefaultSerializer)
            ->withResourceName('user')
            ->toArray();
    }
}
