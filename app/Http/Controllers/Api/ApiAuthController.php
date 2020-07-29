<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate($request->all(), [
            'email' => 'email',
            'password' => 'required',
        ]);
        $email = $request->post('email');
        $screen_name = $request->post('screen_name');
        $password = $request->post('password');

        if (Auth::attempt([
            'email' => $email,
            'screen_name' => $screen_name,
            'password' => $password,
        ])) {
            return [
                'api_token' => Auth::user()->api_token,
            ];
        }

        return response([
            'error' => 'Invalid password or user.',
        ])->setStatusCode(400);
    }
}
