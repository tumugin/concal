<?php

namespace App\Http\Controllers\Page\Stores\Attends\Year\Month\Date;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\CastAttend;
use App\Models\Store;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class DateController extends Controller
{
    public function index(Store $store, int $year, int $month, int $day)
    {
        $base_date = Carbon::createFromDate($year, $month, $day, 'Asia/Tokyo')
            ->setTime(0, 0, 0);
        $end_date = $base_date->clone()->add(CarbonInterval::days(1));
        $attends = CastAttend::whereStoreId($store->id)
            ->whereBetween(
                'start_time',
                [$base_date->utc(), $end_date->utc()],
            )
            ->where('store_id', '=', $store->id)
            ->with('cast')
            ->get();
        $ogp_description = view('ogp.store_attends_day', [
            'attends' => $attends,
        ])->render();
        return SPAPageUtils::renderSPAPage([
            'og_title' => "{$store->store_name}の出勤情報({$year}年{$month}月{$day}日) - コンカフェカレンダー",
            'description' => $ogp_description,
        ]);
    }
}
