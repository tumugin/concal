<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;
use League\Fractal\Resource\Collection;

class CastShowTransformer extends CastTransformer
{
    protected $defaultIncludes = [
        'stores',
        'recentAttends',
    ];

    public function transform(array|Cast $data): array
    {
        return parent::transform($data['cast']);
    }

    public function includeStores(array $data): Collection
    {
        return $this->collection($data['stores'], new CastShowStoreTransformer);
    }

    public function includeRecentAttends(array $data): Collection
    {
        return $this->collection($data['recent_attends'], new CastShowRecentAttendsTransformer);
    }
}
