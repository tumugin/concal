<?php

namespace App\Http\Controllers\Page\Admin\Casts;

use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\Cast;

class AttendController
{
    public function index(Cast $cast)
    {
        return SPAPageUtils::renderSPAPage();
    }
}
