<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Services\UserAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
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
            $user = $userAuthService->attemptLogin($user_name, $email, $password, 'api');
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

    public function revokeTokens()
    {
        return [
            'success' => true,
        ];
    }

    public function userInfo(UserAuthService $userAuthService)
    {
        $user = $userAuthService->getCurrentUser('api');
        return [
            'success' => true,
            // 自分の情報なのでAdmin用のデータを返してしまう
            'info' => $user->getAdminAttributes(),
        ];
    }
}
