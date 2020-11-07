<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Cast;

class CastController
{
    public function show(Cast $cast)
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
