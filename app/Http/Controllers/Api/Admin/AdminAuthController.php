<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Services\AdminUserAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminAuthController extends Controller
{
    public function login(Request $request, AdminUserAuthService $userAuthService)
    {
        $request->validate([
            'email' => 'email',
            'password' => 'required',
        ]);
        $email = $request->post('email');
        $user_name = $request->post('userName');
        $password = $request->post('password');

        try {
            $user = $userAuthService->attemptLogin($user_name, $email, $password);
            return [
                'success' => true,
                'apiToken' => $user->createApiToken(),
            ];
        } catch (LoginFailedException $ex) {
            return response([
                'error' => 'Invalid password or user.',
            ])->setStatusCode(401);
        }
    }

    public function proxyLogin(Request $request)
    {
        if (!config('host.oauth2_proxy_enabled')) {
            return response([
                'error' => 'oauth2-proxy login not enabled.',
            ])->setStatusCode(403);
        }
        // 現在Request内に保持されているCookieをoauth2-proxy側のAPIに渡して認証成功すれば該当するユーザで認証する
        $proxy_response = Http::withCookies(
            $request->cookies->getIterator()->getArrayCopy(),
            $request->getHttpHost()
        )->get(config('host.oauth2_proxy_user_info_endpoint'));
        if (!$proxy_response->ok()) {
            return response([
                'error' => 'Invalid oauth2-proxy cookies.',
            ])->setStatusCode(401);
        }
        $response_json = $proxy_response->json();
        $user = null;
        if ($response_json['email'] ?? false) {
            $user = AdminUser::whereEmail($response_json['email'])->first();
        } else if ($response_json['user'] ?? false) {
            $user = AdminUser::whereUserName($response_json['user'])->first();
        }
        if ($user !== null) {
            return [
                'success' => true,
                'apiToken' => $user->createApiToken(),
            ];
        }
        return response([
            'error' => 'No admin user found.',
        ])->setStatusCode(401);
    }

    public function userInfo(AdminUserAuthService $userAuthService)
    {
        $user = $userAuthService->getCurrentUser();
        return [
            'success' => true,
            'info' => $user->getAdminAttributes(),
        ];
    }
}
