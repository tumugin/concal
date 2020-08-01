<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $user = $userAuthService->attemptLogin($user_name, $email, $password, 'api');
            return [
                'api_token' => $user->createToken('api_token')->accessToken,
            ];
        } catch (LoginFailedException $ex) {
            return response([
                'error' => 'Invalid password or user.',
            ])->setStatusCode(401);
        }
    }

    public function revokeTokens()
    {
        $user = Auth::guard('api')->user();
        if (!$user instanceof User) {
            throw new \Exception('invalid User class.');
        }
        $tokens = $user->tokens();
        foreach ($tokens as $token) {
            $token->revoke();
        }
        return [
            'success' => true,
        ];
    }
}
