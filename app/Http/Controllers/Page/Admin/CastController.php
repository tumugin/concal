<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Cast;

class CastController
{
    public function show(Cast $cast)
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
