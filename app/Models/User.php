<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Webmozart\Assert\Assert;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $user_name
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $user_privilege
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserPrivilege($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $hidden = [
        'password',
    ];

    const USER_PRIVILEGE_ADMIN = 'admin';
    const USER_PRIVILEGE_USER = 'user';
    const USER_PRIVILEGES = [
        self::USER_PRIVILEGE_ADMIN,
        self::USER_PRIVILEGE_USER,
    ];

    const USER_NAME_TEST_REGEX = '/^[a-zA-Z0-9_\-]+$/';

    /**
     * ユーザが管理者権限を持っているかどうか返す
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_privilege === self::USER_PRIVILEGE_ADMIN;
    }

    /**
     * 新しくAPIトークンを発行して発行されたトークンを返す
     *
     * @return string
     */
    public function createApiToken(): string
    {
        return $this->createToken('api_token')->accessToken;
    }

    /**
     * ユーザが所有している全ての認証トークンを失効させる
     */
    public function revokeAllPersonalAccessTokens(): void
    {
        foreach ($this->tokens as $token) {
            $token->revoke();
        }
    }

    /**
     * パスワードを更新する。トランザクション内での使用を想定。
     *
     * @param string $new_password
     */
    private function updatePassword(string $new_password): void
    {
        Assert::stringNotEmpty($new_password);
        $this->password = Hash::make($new_password);
    }

    /**
     * メールアドレスを更新する。トランザクション内での使用を想定。
     *
     * @param string $new_email
     */
    private function updateEmailAddress(string $new_email): void
    {
        Assert::email($new_email);

        // 変更後のメールアドレスを所有しているユーザが0人であることを確認する
        Assert::eq(self::whereEmail($new_email)->count(), 0);

        $this->email = $new_email;
    }

    /**
     * ユーザ名を更新する。トランザクション内での使用を想定。
     *
     * @param string $new_user_name
     */
    private function updateUserName(string $new_user_name): void
    {
        Assert::stringNotEmpty($new_user_name);
        Assert::regex($new_user_name, self::USER_NAME_TEST_REGEX);

        // 変更後のユーザ名を所有しているユーザが0人であることを確認する
        Assert::eq(self::whereUserName($new_user_name)->count(), 0);

        $this->user_name = $new_user_name;
    }

    private function updateName(string $new_name): void
    {
        Assert::stringNotEmpty($new_name);
        $this->name = $new_name;
    }

    /**
     * ユーザ情報を更新する
     *
     * @param array $updated_user_info
     */
    public function updateUserInfo(array $updated_user_info): void
    {
        DB::transaction(function () use ($updated_user_info) {
            if ($updated_user_info['user_name'] !== null) {
                $this->updateUserName($updated_user_info['user_name']);
            }
            if ($updated_user_info['name'] !== null) {
                $this->updateName($updated_user_info['name']);
            }
            if ($updated_user_info['password'] !== null) {
                $this->updatePassword($updated_user_info['password']);
            }
            if ($updated_user_info['email'] !== null) {
                $this->updateEmailAddress($updated_user_info['email']);
            }
            $this->save();
        });
    }

    /**
     * ユーザを作成する
     *
     * @param string $user_name
     * @param string $name
     * @param string $password
     * @param string $email
     * @param string $user_privilege
     * @return User
     */
    public static function createUser(string $user_name, string $name, string $password, string $email, string $user_privilege): User
    {
        Assert::stringNotEmpty($user_name);
        Assert::regex($user_name, self::USER_NAME_TEST_REGEX);
        Assert::stringNotEmpty($name);
        Assert::stringNotEmpty($password);
        Assert::email($email);
        Assert::inArray($user_privilege, self::USER_PRIVILEGES);

        $user = new User();
        $user->user_name = $user_name;
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->user_privilege = $user_privilege;
        $user->save();

        return $user;
    }
}
