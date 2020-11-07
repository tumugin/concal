<?php

namespace App\Http\Controllers\Page\Admin\Groups;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\StoreGroup;

class StoreController extends Controller
{
    public function create(StoreGroup $store_group)
    {
        return SPAPageUtils::renderSPAPage();
    }
}
