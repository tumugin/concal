<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Webmozart\Assert\Assert;

class UserAuthService
{
    /**
     * Auth::guardから現在ログイン中のユーザを取得する
     *
     * @return User
     */
    public static function getCurrentUser(): User
    {
        $user = Auth::guard('api')->user();
        if ($user === null) {
            throw new \Exception('Must be logged in to get current user.');
        }
        return $user;
    }

    /**
     * スクリーンネームもしくはメールアドレスをIDとしてパスワード認証を行う
     *
     * 認証成功時にはUserが返り、失敗時にはLoginFailedExceptionがスローされる
     *
     * @param string|null $user_name
     * @param string|null $mail_address
     * @param string $password
     * @return User
     * @throws LoginFailedException
     */
    public static function attemptLogin(?string $user_name, ?string $mail_address, string $password): User
    {
        Assert::stringNotEmpty($password);
        Assert::nullOrEmail($mail_address);
        Assert::nullOrStringNotEmpty($user_name);
        Assert::true($mail_address !== null || $user_name !== null);

        $credentials_fields = ['password'];
        if ($mail_address !== null) {
            $credentials_fields[] = 'email';
        }
        if ($user_name !== null) {
            $credentials_fields[] = 'user_name';
        }

        $credentials = Arr::only([
            'email' => $mail_address,
            'user_name' => $user_name,
            'password' => $password,
        ], $credentials_fields);

        if (Auth::guard('api')->attempt($credentials)) {
            return Auth::guard('api')->user();
        }
        throw new LoginFailedException();
    }
}
