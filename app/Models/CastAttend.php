<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
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
 * @property-read \App\Models\Store $store
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
 * @property-read \App\Models\Cast $cast
 */
class CastAttend extends Model
{
    protected $guarded = ['id'];

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'cast_id',
                'store_id',
                'attend_info',
                'added_by_user_id',
            ])
            ->merge([
                'start_time' => Carbon::make($this->start_time)->toIso8601String(),
                'end_time' => Carbon::make($this->end_time)->toIso8601String(),
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    public function getUserAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'cast_id',
                'store_id',
                'attend_info',
            ])
            ->merge([
                'start_time' => Carbon::make($this->start_time)->toIso8601String(),
                'end_time' => Carbon::make($this->end_time)->toIso8601String(),
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    /**
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * @return BelongsTo
     */
    public function cast(): BelongsTo
    {
        return $this->belongsTo(Cast::class);
    }
}
