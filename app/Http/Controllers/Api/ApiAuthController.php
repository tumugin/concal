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
        $request->validate($request->all(), [
            'email' => 'email',
            'password' => 'required',
        ]);
        $email = $request->post('email');
        $user_name = $request->post('user_name');
        $password = $request->post('password');

        try {
            $user = $userAuthService->attemptLogin($user_name, $email, $password, 'web');
            return [
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
        $user = $userAuthService::getCurrentUser('api');
        $user->revokeAllPersonalAccessTokens();
        return [
            'success' => true,
        ];
    }
}
