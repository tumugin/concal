<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Webmozart\Assert\Assert;

class UserAuthService
{
    /**
     * スクリーンネームもしくはメールアドレスをIDとしてパスワード認証を行う
     *
     * 認証成功時にはUserが返り、失敗時にはLoginFailedExceptionがスローされる
     *
     * @param string|null $screen_name
     * @param string|null $mail_address
     * @param string $password
     * @param string|null $guard_name
     * @return User
     * @throws LoginFailedException
     */
    public function attemptLogin(?string $screen_name, ?string $mail_address, string $password, ?string $guard_name): User
    {
        $user_identifier_satisfied = false;
        if ($screen_name !== null) {
            Assert::stringNotEmpty($screen_name);
            $user_identifier_satisfied = true;
        }
        if ($mail_address !== null) {
            Assert::email($mail_address);
            $user_identifier_satisfied = true;
        }
        Assert::true($user_identifier_satisfied);
        Assert::stringNotEmpty($password);

        if (Auth::guard($guard_name)->attempt([
            'email' => $mail_address,
            'screen_name' => $screen_name,
            'password' => $password,
        ])) {
            return Auth::user();
        }
        throw new LoginFailedException();
    }
}
