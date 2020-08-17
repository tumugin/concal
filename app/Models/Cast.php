<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\Assert;

/**
 * App\Models\Cast
 *
 * @property int $cast_id
 * @property string $cast_name
 * @property string|null $cast_short_name
 * @property string|null $cast_twitter_id
 * @property string $cast_description
 * @property string|null $cast_color
 * @property int $cast_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cast extends Model
{
    protected $primaryKey = 'cast_id';

    /**
     * キャストを追加する
     * @param array $cast_info
     * @return int 追加されたキャストのID
     */
    public static function addCast(array $cast_info): int
    {
        Assert::string($cast_info['cast_name']);
        Assert::nullOrString($cast_info['cast_short_name'] ?? null);
        Assert::nullOrString($cast_info['cast_twitter_id'] ?? null);
        Assert::string($cast_info['cast_description']);
        if (isset($cast_info['cast_color'])) {
            Assert::regex($cast_info['cast_color'], '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$');
        }

        $cast = new Cast();
        $cast->cast_name = $cast_info['cast_name'];
        $cast->cast_short_name = $cast_info['cast_short_name'] ?? null;
        $cast->cast_twitter_id = $cast_info['cast_twitter_id'] ?? null;
        $cast->cast_description = $cast_info['cast_description'];
        $cast->cast_color = $cast_info['cast_color'] ?? null;
        $cast->cast_disabled = 0;
        $cast->save();

        return $cast->cast_id;
    }

    public function getAttends(): HasMany
    {
        return $this->hasMany(CastAttend::class);
    }

    public function getStoreCasts(): HasMany
    {
        return $this->hasMany(StoreCast::class);
    }

    /**
     * キャストを永久追放(物理削除)する
     */
    public function deleteCast(): void
    {
        DB::transaction(function () {
            self::getAttends()->delete();
            self::getStoreCasts()->delete();
            self::delete();
        });
    }

    /**
     * キャストを卒業させる
     */
    public function graduateCast(): void
    {
        $this->cast_disabled = 1;
        $this->save();
    }

    /**
     * キャストを店舗に在籍させる
     *
     * @param Store $store 在籍させる店舗
     */
    public function enrollToStore(Store $store): void
    {
        $store->enrollCast($this);
    }

    /**
     * キャストの出勤情報を登録する
     *
     * @param int $store_id
     * @param int $added_by_user_id
     * @param Carbon $start_time
     * @param Carbon $end_time
     * @param string $attend_info
     * @return int 出勤情報のID
     */
    public function addAttendance(
        int $store_id, int $added_by_user_id, Carbon $start_time, Carbon $end_time, string $attend_info
    ): int
    {
        return CastAttend::addAttendance(
            $this->cast_id,
            $store_id,
            $added_by_user_id,
            $start_time,
            $end_time,
            $attend_info
        );
    }
}
