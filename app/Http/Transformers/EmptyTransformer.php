<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class EmptyTransformer extends TransformerAbstract
{
    public function transform(): array
    {
        return [];
    }
}
