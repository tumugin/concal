<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\StoreStoreGroup;
use App\Http\Requests\Admin\UpdateStoreGroup;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\StoreGroupTransformer;
use App\Http\Transformers\EmptyTransformer;
use App\Models\StoreGroup;

class AdminStoreGroupController
{
    private const _PAGINATION_COUNT = 20;

    public function index()
    {
        $store_groups = StoreGroup::query()->paginate(self::_PAGINATION_COUNT);
        return fractal($store_groups, new StoreGroupTransformer, new DefaultSerializer)
            ->withResourceName('storeGroups')
            ->toArray();
    }

    public function show(StoreGroup $group)
    {
        return fractal($group, new StoreGroupTransformer, new DefaultSerializer)
            ->withResourceName('storeGroup')
            ->toArray();
    }

    public function store(StoreStoreGroup $request)
    {
        $store_group = new StoreGroup($request->toValueObject());
        $store_group->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->addMeta([
                'id' => $store_group->id,
            ])
            ->toArray();
    }

    public function update(UpdateStoreGroup $request, StoreGroup $group)
    {
        $group->update($request->toValueObject());
        $group->save();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }

    public function destroy(StoreGroup $group)
    {
        $group->delete();
        return fractal(null, new EmptyTransformer, new DefaultSerializer)
            ->toArray();
    }
}
