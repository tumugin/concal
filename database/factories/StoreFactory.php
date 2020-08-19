<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Store;
use App\Models\StoreCast;
use App\Models\StoreGroup;

$factory->define(StoreGroup::class, function () {
    return [
        'group_name' => 'アフィリア魔法王国',
    ];
});

$factory->define(Store::class, function () {
    return [
        'store_name' => 'アフィリア・クロニクルS',
        'store_group_id' => 0,
        'store_disabled' => 0,
    ];
});

$factory->define(StoreCast::class, function () {
    return [
        'store_id' => 0,
        'cast_id' => 0,
    ];
});
