<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class TopPageController extends Controller
{
    public function index()
    {
        return SPAPageUtils::renderSPAPage();
    }
}
