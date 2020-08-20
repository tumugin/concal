<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        Assert::regex($user_name, '/^[a-zA-Z0-9_\-]+$/');
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
