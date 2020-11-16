<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Services\AdminUserAuthService;
use Illuminate\Http\Request;

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

    public function revokeTokens()
    {
        return [
            'success' => true,
        ];
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
