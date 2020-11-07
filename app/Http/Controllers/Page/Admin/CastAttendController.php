<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Cast;

class CastAttendController
{
    public function index(Cast $cast)
    {
        return SPAPageUtils::renderSPAPage();
    }
}
