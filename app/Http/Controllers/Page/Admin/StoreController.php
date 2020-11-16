<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Store;

class StoreController
{
    public function show(Store $store)
    {
        return SPAPageUtils::renderAdminSPAPage();
    }

    public function index()
    {
        return SPAPageUtils::renderAdminSPAPage();
    }

    public function create()
    {
        return SPAPageUtils::renderAdminSPAPage();
    }
}
