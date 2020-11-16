<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Webmozart\Assert\Assert;

/**
 * App\Models\AdminUser
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
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUserPrivilege($value)
 * @mixin \Eloquent
 */
class AdminUser extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $hidden = [
        'password',
    ];

    const USER_PRIVILEGE_ADMIN = 'admin';
    const USER_PRIVILEGE_SUPER_ADMIN = 'super_admin';
    const USER_PRIVILEGES = [
        self::USER_PRIVILEGE_ADMIN,
        self::USER_PRIVILEGE_SUPER_ADMIN,
    ];

    const USER_NAME_TEST_REGEX = '/^[a-zA-Z0-9_\-]+$/';

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'user_name',
                'name',
                'email',
                'user_privilege',
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    /**
     * 十分に強力なパスワードかどうか検証する
     *
     * @param string $password
     */
    public static function assertStrongPassword(string $password): void
    {
        Assert::minLength($password, 10);
        Assert::regex($password, '/[a-z]/');
        Assert::regex($password, '/[A-Z]/');
        Assert::regex($password, '/[0-9]/');
        // すべてマルチバイト文字でない
        Assert::regex($password, '/^[[:ascii:]]+$/');
        // 制御文字を含まない
        Assert::notRegex($password, '/[\x00-\x1F]/');
        Assert::notRegex($password, '/[\x20]/');
        Assert::notRegex($password, '/[\x7F]/');
    }

    /**
     * 新しくAPIトークンを発行して発行されたトークンを返す
     *
     * @return string
     */
    public function createApiToken(): string
    {
        return resolve(JWT::class)->fromUser($this);
    }

    /**
     * パスワードを更新する。トランザクション内での使用を想定。
     *
     * @param string $new_password
     */
    private function updatePassword(string $new_password): void
    {
        Assert::stringNotEmpty($new_password);
        self::assertStrongPassword($new_password);
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

        if ($new_email !== $this->email) {
            // 変更後のメールアドレスを所有しているユーザが0人であることを確認する
            Assert::eq(self::whereEmail($new_email)->count(), 0);
        }

        $this->email = $new_email;
    }

    /**
     * ユーザ権限を更新する。トランザクション内での使用を想定。
     *
     * @param string $new_user_privilege
     */
    private function updateUserPrivilege(string $new_user_privilege): void
    {
        Assert::inArray($new_user_privilege, self::USER_PRIVILEGES);
        $this->user_privilege = $new_user_privilege;
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

        if ($new_user_name !== $this->user_name) {
            // 変更後のユーザ名を所有しているユーザが0人であることを確認する
            Assert::eq(self::whereUserName($new_user_name)->count(), 0);
        }

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
            if ($updated_user_info['user_privilege'] !== null) {
                $this->updateUserPrivilege($updated_user_info['user_privilege']);
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
     * @return AdminUser
     */
    public static function createUser(string $user_name, string $name, string $password, string $email, string $user_privilege): AdminUser
    {
        Assert::inArray($user_privilege, self::USER_PRIVILEGES);

        $user = new AdminUser();
        $user->updateUserName($user_name);
        $user->updateName($name);
        $user->updatePassword($password);
        $user->updateEmailAddress($email);
        $user->user_privilege = $user_privilege;
        $user->save();

        return $user;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}