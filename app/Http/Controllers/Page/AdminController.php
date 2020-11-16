<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return SPAPageUtils::renderAdminSPAPage();
    }
}
