<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\StoreGroup;

class StoreGroupController extends Controller
{
    public function show(StoreGroup $store_group)
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
