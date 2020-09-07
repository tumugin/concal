<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Services\UserAuthService;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function login(Request $request, UserAuthService $userAuthService)
    {
        $request->validate([
            'email' => 'email',
            'password' => 'required',
        ]);
        $email = $request->post('email');
        $user_name = $request->post('userName');
        $password = $request->post('password');

        try {
            $user = $userAuthService->attemptLogin($user_name, $email, $password, 'web');
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

    public function revokeTokens(UserAuthService $userAuthService)
    {
        $user = $userAuthService->getCurrentUser('api');
        $user->revokeAllPersonalAccessTokens();
        return [
            'success' => true,
        ];
    }

    public function userInfo()
    {
        $user = UserAuthService::getCurrentUser('api');
        return [
            'success' => true,
            // 自分の情報なのでAdmin用のデータを返してしまう
            'info' => $user->getAdminAttributes(),
        ];
    }
}
