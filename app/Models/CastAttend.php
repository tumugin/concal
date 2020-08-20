<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * App\Models\CastAttend
 *
 * @property int $id
 * @property int $cast_id
 * @property int $store_id
 * @property string $start_time
 * @property string $end_time
 * @property string $attend_info
 * @property int $added_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereAddedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereAttendInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CastAttend extends Model
{
    /**
     * キャストの出勤情報を登録する
     *
     * @param int $cast_id
     * @param int $store_id
     * @param int $added_by_user_id
     * @param Carbon $start_time
     * @param Carbon $end_time
     * @param string $attend_info
     * @return CastAttend 出勤情報
     */
    public static function addAttendance(
        int $cast_id, int $store_id, int $added_by_user_id, Carbon $start_time, Carbon $end_time, string $attend_info
    ): CastAttend
    {
        Assert::false($start_time->unix() > $end_time->unix(), '開始時間が終了時間より未来は不正');

        $cast_attend = new CastAttend();
        $cast_attend->cast_id = $cast_id;
        $cast_attend->store_id = $store_id;
        $cast_attend->start_time = $start_time->toDateTimeString();
        $cast_attend->end_time = $end_time->toDateTimeString();
        $cast_attend->added_by_user_id = $added_by_user_id;
        $cast_attend->attend_info = $attend_info;
        $cast_attend->save();

        return $cast_attend;
    }

    /**
     * キャストの出勤情報を更新する
     *
     * @param int $store_id
     * @param int $added_by_user_id
     * @param Carbon $start_time
     * @param Carbon $end_time
     * @param string $attend_info
     */
    public function updateAttendance(
        int $store_id, int $added_by_user_id, Carbon $start_time, Carbon $end_time, string $attend_info
    ): void
    {
        Assert::false($start_time->unix() > $end_time->unix(), '開始時間が終了時間より未来は不正');

        $this->store_id = $store_id;
        $this->start_time = $start_time->toDateTimeString();
        $this->end_time = $end_time->toDateTimeString();
        $this->added_by_user_id = $added_by_user_id;
        $this->attend_info = $attend_info;
        $this->save();
    }
}
