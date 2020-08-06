<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CastAttend
 *
 * @property int $cast_attend_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereCastAttendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastAttend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CastAttend extends Model
{
    protected $primaryKey = 'cast_attend_id';
}
