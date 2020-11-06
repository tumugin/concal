<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Cast;

class CastController extends Controller
{
    public function show(Cast $cast)
    {
        return SPAPageUtils::renderSPAPage([
            'og_title' => "{$cast->cast_name} - コンカフェカレンダー",
        ]);
    }
}
