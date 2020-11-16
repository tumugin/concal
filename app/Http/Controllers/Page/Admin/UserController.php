<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
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
