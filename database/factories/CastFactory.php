<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cast;
use App\Models\CastAttend;
use Carbon\Carbon;

$factory->define(Cast::class, function () {
    return [
        'cast_name' => 'ウジュ・マッチャ・ミルク',
        'cast_short_name' => 'ウジュ',
        'cast_twitter_id' => 'uju_afilia',
        'cast_description' => '',
        'cast_color' => '#00bfff',
        'cast_disabled' => 0,
    ];
});

$factory->define(CastAttend::class, function () {
    return [
        'cast_id' => 0,
        'store_id' => 0,
        'start_time' => Carbon::parse('2020/08/19 15:00:00')->toDateTimeString(),
        'end_time' => Carbon::parse('2020/08/19 23:00:00')->toDateTimeString(),
        'attend_info' => '',
        'added_by_user_id' => 0,
    ];
});
