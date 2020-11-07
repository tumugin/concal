<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
    {
        return SPAPageUtils::renderSPAPage([
            'og_title' => "ログイン - コンカフェカレンダー",
        ]);
    }
}
