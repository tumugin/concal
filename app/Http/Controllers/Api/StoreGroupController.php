<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreGroup;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreGroupController extends Controller
{
    public function index()
    {
        $store_groups = StoreGroup::query()
            ->with('stores', fn(HasMany $builder) => $builder->active())
            ->paginate(10);
        $mapped_store_groups = collect($store_groups->items())
            ->map(fn($item) => collect($item->getUserAttributes())
                ->merge([
                    'stores' => $item
                        ->stores
                        ->map(
                            fn($store) => $store->getUserAttributes()
                        ),
                ])
            );
        return [
            'success' => true,
            'data' => [
                'storeGroups' => $mapped_store_groups,
                'pageCount' => $store_groups->lastPage(),
                'nextPage' => $store_groups->hasMorePages() ? $store_groups->currentPage() + 1 : null,
            ],
        ];
    }

    public function show(StoreGroup $group)
    {
        if ($group === null) {
            return response([
                'error' => 'Store group not found.',
            ])->setStatusCode(404);
        }
        return [
            'success' => true,
            'data' => [
                'storeGroup' => $group->getUserAttributes(),
                'stores' => $group
                    ->stores
                    ->active()
                    ->map(fn($store) => $store->getUserAttributes()),
            ],
        ];
    }
}
