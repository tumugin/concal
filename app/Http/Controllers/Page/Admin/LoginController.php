<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;

class LoginController extends Controller
{
    public function index()
    {
        return SPAPageUtils::renderAdminSPAPage();
    }
}
