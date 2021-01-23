<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\Admin\AdminUserTransformer;
use App\Http\Transformers\EmptyTransformer;
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
            $api_token = resolve(AdminUserAuthService::class)->createApiToken($user);
            return fractal($api_token, new EmptyTransformer, new DefaultSerializer)
                ->withResourceName('apiToken')
                ->toArray();
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
        $oauth2_proxy_endpoint = config('host.oauth2_proxy_user_info_endpoint');
        $proxy_response = Http::withCookies(
            $request->cookies->getIterator()->getArrayCopy(),
            parse_url($oauth2_proxy_endpoint, PHP_URL_HOST)
        )->get($oauth2_proxy_endpoint);
        if (!$proxy_response->ok()) {
            return response([
                'error' => 'Invalid oauth2-proxy cookies.',
            ])->setStatusCode(400);
        }
        $response_json = $proxy_response->json();
        $user = null;
        if ($response_json['email'] ?? false) {
            $user = AdminUser::whereEmail($response_json['email'])->first();
        } else if ($response_json['user'] ?? false) {
            $user = AdminUser::whereUserName($response_json['user'])->first();
        }
        if ($user !== null) {
            $api_token = resolve(AdminUserAuthService::class)->createApiToken($user);
            return fractal($api_token, new EmptyTransformer, new DefaultSerializer)
                ->withResourceName('apiToken')
                ->toArray();
        }
        return response([
            'error' => 'No admin user found.',
        ])->setStatusCode(401);
    }

    public function userInfo(AdminUserAuthService $userAuthService)
    {
        $user = $userAuthService->getCurrentUser();
        return fractal($user, new AdminUserTransformer, new DefaultSerializer)
            ->withResourceName('info')
            ->toArray();
    }
}
