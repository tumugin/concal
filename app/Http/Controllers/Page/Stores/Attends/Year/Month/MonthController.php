<?php

namespace App\Http\Controllers\Page\Stores\Attends\Year\Month;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Store;

class MonthController extends Controller
{
    public function index(Store $store, int $year, int $month)
    {
        return SPAPageUtils::renderSPAPage([
            'og_title' => "{$store->store_name}の出勤情報({$year}年{$month}月) - コンカフェカレンダー",
        ]);
    }
}
