<?php

namespace App\Http\Transformers\Api;

use App\Models\Cast;
use League\Fractal\TransformerAbstract;

class CastTransformer extends TransformerAbstract
{
    public function transform(Cast $cast): array
    {
        return [
            'id' => $cast->id,
            'castName' => $cast->cast_name,
            'castShortName' => $cast->cast_short_name,
            'castTwitterId' => $cast->cast_twitter_id,
            'castDescription' => $cast->cast_description,
            'castColor' => $cast->cast_color,
            'castDisabled' => $cast->cast_disabled,
        ];
    }
}
