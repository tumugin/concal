<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;
use Webmozart\Assert\Assert;

class AdminUserAuthService
{
    /**
     * Auth::guardから現在ログイン中のユーザを取得する
     *
     * @return AdminUser
     * @throws AuthorizationException
     */
    public function getCurrentUser(): AdminUser
    {
        /** @var $user AdminUser */
        $user = Auth::guard('admin_api')->user();
        if ($user === null) {
            throw new AuthorizationException('Must be logged in to get current user.');
        }
        return $user;
    }

    /**
     * スクリーンネームもしくはメールアドレスをIDとしてパスワード認証を行う
     *
     * 認証成功時にはAdminUserが返り、失敗時にはLoginFailedExceptionがスローされる
     *
     * @param string|null $user_name
     * @param string|null $mail_address
     * @param string $password
     * @return AdminUser
     * @throws LoginFailedException
     */
    public function attemptLogin(?string $user_name, ?string $mail_address, string $password): AdminUser
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

        if (Auth::guard('admin_api')->attempt($credentials)) {
            /** @var $user AdminUser */
            $user = Auth::guard('admin_api')->user();
            return $user;
        }
        throw new LoginFailedException();
    }

    /**
     * 新しくAPIトークンを発行して発行されたトークンを返す
     *
     * @param AdminUser $admin_user 発行する対象の管理者ユーザ
     * @return string
     */
    public function createApiToken(AdminUser $admin_user): string
    {
        // 管理画面は14日間有効のトークンを発行する
        $token_expire_time = 60 * 24 * 14;
        /** @var $jwt JWT */
        $jwt = resolve(JWT::class);
        $jwt->factory()->setTTL($token_expire_time);
        return $jwt->fromUser($admin_user);
    }
}
