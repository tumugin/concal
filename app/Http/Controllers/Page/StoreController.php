<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreController extends Controller
{
    public function show(Store $store)
    {
        return SPAPageUtils::renderSPAPage([
            'title' => "{$store->store_name} - コンカフェカレンダー",
        ]);
    }
}
