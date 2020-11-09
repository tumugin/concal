<?php

namespace App\Http\Middleware;

use App\Exceptions\WrongAPIKeyException;
use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $api_key = config('apikey.app_api_key');
        $header_api_key = $request->header('X-API-KEY');
        if ($api_key !== $header_api_key) {
            throw new WrongAPIKeyException('API KEY miss match.');
        }
        return $next($request);
    }
}
