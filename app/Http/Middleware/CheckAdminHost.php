<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CheckAdminHost
{
    /**
     * 本番環境では管理画面を別ホストで配信するため、ユーザに見せているホストの方で管理画面を見られないようにするためのMiddleware
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->getHost() === Config::get('host.admin_host')) {
            return $next($request);
        }
        abort(404);
        return false;
    }
}
