<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Page\SPAPageUtils;
use App\Models\AdminUser;

class AdminUserController extends Controller
{
    public function show(AdminUser $admin_user)
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
