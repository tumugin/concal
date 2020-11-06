<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class StoreGroupController extends Controller
{
    public function index()
    {
        return SPAPageUtils::renderSPAPage([
            'og_title' => "店舗グループ一覧 - コンカフェカレンダー",
        ]);
    }
}
