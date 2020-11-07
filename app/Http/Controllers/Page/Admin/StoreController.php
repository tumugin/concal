<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Store;

class StoreController
{
    public function show(Store $store)
    {
        return SPAPageUtils::renderSPAPage();
    }

    public function index()
    {
        return SPAPageUtils::renderSPAPage();
    }

    public function create()
    {
        return SPAPageUtils::renderSPAPage();
    }
}
