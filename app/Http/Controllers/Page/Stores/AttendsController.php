<?php

namespace App\Http\Controllers\Page\Stores;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Store;

class AttendsController extends Controller
{
    public function index(Store $store)
    {
        return SPAPageUtils::renderSPAPage([
            'og_title' => "{$store->store_name}の出勤情報 - コンカフェカレンダー",
        ]);
    }
}
