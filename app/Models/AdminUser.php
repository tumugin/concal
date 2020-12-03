<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;
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

    protected $guarded = ['id'];

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
     * 新しくAPIトークンを発行して発行されたトークンを返す
     *
     * @return string
     */
    public function createApiToken(): string
    {
        $token_expire_time = 60 * 24 * 14; // 14日間
        $jwt = resolve(JWT::class);
        $jwt->factory()->setTTL($token_expire_time);
        return $jwt->fromUser($this);
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
