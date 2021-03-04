<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\StoreGroupIndexTransformer;
use App\Http\Transformers\Api\StoreGroupShowTransformer;
use App\Models\StoreGroup;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreGroupController extends Controller
{
    public function index()
    {
        $store_groups = StoreGroup::query()
            ->with('stores', fn(HasMany $builder) => $builder->active())
            ->paginate(10);
        $result = fractal($store_groups, new StoreGroupIndexTransformer, new DefaultSerializer)
            ->withResourceName('storeGroups')
            ->toArray();
        return [
            'data' => $result,
        ];
    }

    public function show(StoreGroup $group)
    {
        $result = fractal($group, new StoreGroupShowTransformer, new DefaultSerializer)
            ->withResourceName('storeGroup')
            ->toArray();
        return [
            'data' => $result,
        ];
    }
}
