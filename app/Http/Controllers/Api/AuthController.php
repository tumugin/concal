<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Http\Serializers\DefaultSerializer;
use App\Http\Transformers\Api\SelfTransformer;
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
            $user = $userAuthService->attemptLogin($user_name, $email, $password);
            return [
                'apiToken' => $userAuthService->createApiToken($user),
            ];
        } catch (LoginFailedException $ex) {
            return response([
                'error' => 'Invalid password or user.',
            ])->setStatusCode(401);
        }
    }

    public function userInfo(UserAuthService $userAuthService)
    {
        $user = $userAuthService->getCurrentUser();
        return fractal($user, new SelfTransformer, new DefaultSerializer)
            ->withResourceName('info')
            ->toArray();
    }
}
